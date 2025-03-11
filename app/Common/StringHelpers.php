<?php

namespace App\Common;

use App\Enums\CandidateEnum;
use Illuminate\Support\Carbon;

class StringHelpers
{

    //Sẽ disable các trạng thái trước status hiện tại
    public static function getSelectCandidateEnumOptions($enumOption, $selected = '')
    {
        $html = '<option></option>';
        $currentOrder = CandidateEnum::getOrder($selected);
        foreach ($enumOption as $option) {
            $optionOrder = CandidateEnum::getOrder($option->name);
            $disabled = ($optionOrder < $currentOrder && $option->name !== CandidateEnum::employee) ? 'disabled' : ''; // Kiểm tra nếu option đang xét có thứ tự nhỏ hơn trạng thái hiện tại và không phải là trạng thái "employee".
            $html .= '<option value="' . $option->name . '" ' . $disabled . ' ' . ((is_array($selected) ? in_array($option->name, $selected) : $selected == $option->name) ? 'selected' : '') . '>' . $option->value . '</option>';
        }
        return $html;
    }
    public static function getSelectArrayEnumOptions($enumOption, $selected = '')
    {
        $html = '<option></option>';
        foreach ($enumOption as $option) {
            $html .= '<option value="' . $option->name . '"' . ((is_array($selected) ? in_array($option->name, $selected) : $selected == $option->name) ? 'selected' : '') . '>' . $option['value']->value . '</option>';
        }
        return $html;
    }
    public static function getSelectEnumOptions($enumOption, $selected = '')
    {
        $html = '<option></option>';
        foreach ($enumOption as $option) {
            $html .= '<option value="' . $option->name . '"' . ((is_array($selected) ? in_array($option->name, $selected) : $selected == $option->name) ? 'selected' : '') . '>' . $option->value . '</option>';
        }
        return $html;
    }
    public static function getSelectOptions($options, $selected = '')
    {
        $html = '<option></option>';
        foreach ($options as $option) {
            $html .= '<option value="' . $option->id . '"' . ((is_array($selected) ? in_array($option->id, $selected) : $selected == $option->id) ? 'selected' : '') . '>' . $option->name . '</option>';
        }
        return $html;
    }
    public static function getSelectRoleOptions($options, $selected = '')
    {
        $html = '<option></option>';
        foreach ($options as $option) {
            $html .= '<option value="' . $option->id . '"' . ((is_array($selected) ? in_array($option->id, $selected) : $selected == $option->id) ? 'selected' : '') . '>' . $option->name . '</option>';
        }
        return $html;
    }

    public static function getSelectProvinceOptions($options, $selected = '')
    {
        $html = '<option></option>';
        foreach ($options as $option) {
            $html .= '<option value="' . $option->code . '"' . ((is_array($selected) ? in_array($option->code, $selected) : $selected == $option->code) ? 'selected' : '') . '>' . $option->name . '</option>';
        }
        return $html;
    }
    public static function getSelectOptionsNormal($options, $selected = '')
    {
        $html = '';
        foreach ($options as $option) {
            $html .= '<option value="' . $option->id . '"' . ((is_array($selected) ? in_array($option->id, $selected) : $selected == $option->id) ? 'selected' : '') . '>' . $option->title . '</option>';
        }
        return $html;
    }

    public static function getSelectMonth($selected = '')
    {
        $html = '';
        $monthNow = Carbon::now()->format('m');
        for ($i = 1; $i <= 12; $i++) {
            if ($selected != null) {
                $html .= '<option value="' . $i . '"' . ($i == $selected ? 'selected' : '') . '>Tháng ' . $i . '</option>';
            } else {
                $html .= '<option value="' . $i . '"' . ($i == $monthNow ? 'selected' : '') . '>Tháng ' . $i . '</option>';
            }

        }
        return $html;
    }

    public static function getSelectYear($selected = '')
    {
        $html = '';
        $yearNow = Carbon::now()->format('Y');
        for ($i = 1960; $i <= 2100; $i++) {
            if ($selected != null) {
                $html .= '<option value="' . $i . '"' . ($i == $selected ? 'selected' : '') . '>Năm ' . $i . '</option>';
            } else {
                $html .= '<option value="' . $i . '"' . ($i == $yearNow ? 'selected' : '') . '>Năm ' . $i . '</option>';
            }
        }
        return $html;
    }

    public static function convertName($name)
    {
        return mb_convert_case(ucwords($name), MB_CASE_TITLE, "UTF-8");
    }

    public static function slug($str)
    {
        $str = trim(mb_strtolower($str));
        $str = preg_replace('/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/', 'a', $str);
        $str = preg_replace('/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/', 'e', $str);
        $str = preg_replace('/(ì|í|ị|ỉ|ĩ)/', 'i', $str);
        $str = preg_replace('/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/', 'o', $str);
        $str = preg_replace('/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/', 'u', $str);
        $str = preg_replace('/(ỳ|ý|ỵ|ỷ|ỹ)/', 'y', $str);
        $str = preg_replace('/(đ)/', 'd', $str);
        $str = preg_replace('/[^a-z0-9-\s]/', '', $str);
        $str = preg_replace('/([\s]+)/', '-', $str);
        $str = preg_replace('/---/', '-', $str);
        return $str;
    }

    public static function removeVietnameseSign($str)
    {
        $str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $str);
        $str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $str);
        $str = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $str);
        $str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $str);
        $str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $str);
        $str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $str);
        $str = preg_replace("/(đ)/", 'd', $str);
        $str = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", 'A', $str);
        $str = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'E', $str);
        $str = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", 'I', $str);
        $str = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", 'O', $str);
        $str = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'U', $str);
        $str = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", 'Y', $str);
        $str = preg_replace("/(Đ)/", 'D', $str);
        return $str;
    }

    public static function getAlias($str)
    {
        $str = strip_tags($str);
        $str = self::removeVietnameseSign($str);
        $allowed = "/[^A-Za-z0-9- ]/i";
        $str = preg_replace($allowed, '', $str);
        $str = trim($str);
        while (strpos($str, '  ') !== FALSE) {
            $str = str_replace('  ', ' ', $str);
        }
        $str = str_replace(' ', '-', $str);
        while (strpos($str, '--') !== FALSE) {
            $str = str_replace('--', '-', $str);
        }
        $str = strtolower($str);
        return $str;
    }

    public static function getTextInput($name, $value, $options = null)
    {
        return '
			<div class="form-group col-md-12">
				<input type="text" name="' . $name . '" class="form-control" placeholder="" value="' . $value . '"/>
            </div>';
    }

    public static function getNumberInput($name, $value, $options = null)
    {
        return '
			<div class="form-group col-md-12">
				<input type="number" name="' . $name . '" class="form-control" placeholder="" value="' . $value . '"/>
            </div>';
    }

    public static function getHtmlInput($name, $value, $options = null)
    {
        return '
			<div class="form-group col-md-12">
				<textarea class="ckeditor" rows="5" cols="5" name="' . $name . '">' . $value . '</textarea>
			</div>';
    }

    public static function getRadioInput($name, $value, $options = null)
    {
        return '<div class="form-group col-md-12">
					<label class="radio-inline">
						<input type="radio" name="' . $name . '" class="styled" value="1" ' . ($value ? 'checked' : '') . '>
						Hiển thị
					</label>

					<label class="radio-inline">
						<input type="radio" name="' . $name . '" value="0" class="styled"' . (!$value ? 'checked' : '') . '>
						Ẩn
					</label>
				</div>';
    }

    public static function getColorInput($name, $value, $options = null)
    {
        return '
			<div class="form-group col-md-12">
                <input type="text"class="form-control colorpicker-show-input" data-preferred-format="hex" name="' . $name . '" value="' . $value . '">
			</div>';
    }

    public static function getImageInput($name, $value, $options = null)
    {
        $guid = '';
        if (!empty($options['guid']))
            $guid = $options['guid'];
        $id = $guid . '_' . $name;
        return '
            <div class="form-group col-md-12">
				<div class="div-image">
					<input type="file" data-guid="' . $guid . '" multiple="" id="' . $id . '"data-value="' . $value . '" data-field="' . $name . '" class="file-input-overwrite" data-show-upload="false" data-show-remove="true" onclick="BrowseServer(\'' . $id . '\',\'' . $guid . '\')"/>
					<input type="hidden" class="image_data" data-guid="' . $guid . '" value="' . $value . '" name="' . $name . '"/>
                    <span class="help-block">Chỉ cho phép các file ảnh có đuôi <code>jpg</code>, <code>gif</code> và <code>png</code></span>
                </div>
            </div>';
    }

}
