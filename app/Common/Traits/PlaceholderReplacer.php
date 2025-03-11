<?php
// app/Traits/PlaceholderReplacerTrait.php

namespace App\Common\Traits;

//Trait này giúp tìm các giá trị bên trong {{}}
trait PlaceholderReplacer
{
    public function replaceGender($htmlContent, $genderValue)
    {
        if ($genderValue === 'male') {
            return str_replace('{{gender}}', 'Anh', $htmlContent);
        } else {
            return str_replace('{{gender}}', 'Chị', $htmlContent);
        }
    }
    /**
     * Lấy giá trị từ object dựa trên chuỗi đặc tả.
     *
     * @param string $keys
     * @param object $object
     * @return mixed
     */
    private function getValueFromObject($keys, $object)
    {
        if (!is_object($object))
            return null;

        $keys = explode('.', $keys);
        $key = array_shift($keys);

        if (method_exists($object, $key)) {
            $potentialRelation = $object->$key();

            if ($potentialRelation instanceof \Illuminate\Database\Eloquent\Relations\Relation) {
                if ($potentialRelation instanceof \Illuminate\Database\Eloquent\Relations\HasMany) {
                    // Đối với quan hệ hasMany, chúng ta sẽ chỉ lấy ra mục mới nhất
                    $object = $object->$key()->latest()->first();
                } else {
                    $object = $object->$key;
                }
            } elseif (isset($object->$key)) {
                $object = $object->$key;
            } else {
                return null;
            }
        } elseif (isset($object->$key)) {
            $object = $object->$key;
        } else {
            return null;
        }

        // Đoạn này lấy tất cả giá trị 1-n chứ không lấy giá trị mới nhất
        // Nếu chúng ta đang xử lý một collection và vẫn còn keys để xử lý, chúng ta sẽ trả về một mảng chứa giá trị của thuộc tính đó từ tất cả các mục trong collection
        // if ($object instanceof \Illuminate\Database\Eloquent\Collection && count($keys) > 0) {
        //     $results = [];
        //     foreach ($object as $item) {
        //         $results[] = $this->getValueFromObject(join('.', $keys), $item);
        //     }
        //     return implode(', ', $results);
        // }

        return (count($keys) == 0) ? $object : $this->getValueFromObject(join('.', $keys), $object);
    }



    /**
     * Thay thế placeholders trong chuỗi với giá trị thực tế từ object.
     *
     * @param string $content
     * @param object $object
     * @return string
     */
    public function replacePlaceholders($content, $object)
    {
        preg_match_all('/\{\{(.*?)\}\}/', $content, $matches);
        $placeholders = $matches[1];
        $search = [];
        $replace = [];
        foreach ($placeholders as $placeholder) {
            $changePlaceholder = explode('.', $placeholder);
            unset($changePlaceholder[0]);
            $changePlaceholder = implode('.', $changePlaceholder);
            $value = $this->getValueFromObject($changePlaceholder, $object);
            if (is_object($value) || is_array($value)) {
                continue;
            }

            // Nếu giá trị là null, thay thế bằng chuỗi rỗng
            $replace[] = $value ?? '';
            $search[] = '{{' . $placeholder . '}}';
        }

        return str_replace($search, $replace, $content);
    }

    /* Lấy danh sách {{model.field}} hoặc {{model.relation.field}}
     *
     * @param string $content
     * @param object $object
     * @return string
     */
    public function getValueFromPlaceholder($object, $placeholder)
    {
        $parts = explode('.', $placeholder);

        foreach ($parts as $part) {
            if (method_exists($object, $part) && is_a($object->$part(), 'Illuminate\Database\Eloquent\Relations\Relation')) {
                //Nếu là 1 quan hệ
                $object = $object->$part()->getResults();
            } elseif (isset($object->$part)) {
                //Nếu là 1 thuộc tính
                $object = $object->$part;
            } else {
                return null;
            }
        }

        // Nếu đối tượng cuối cùng là một mối quan hệ "nhiều dạng" (hasMany)
        if ($object instanceof \Illuminate\Database\Eloquent\Collection) {
            dd('many');
            $lastKey = end($parts);
            $values = $object->pluck($lastKey); // lấy giá trị từ collection dựa vào key
            return $values->implode(', '); // nối tất cả các giá trị lại với nhau
        }

        return $object;
    }

}