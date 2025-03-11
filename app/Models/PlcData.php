<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlcData extends Model
{
    protected $table = "plc_data";

    protected $fillable = [
        'machine_id',
        'datalog_date',                     // Ngày (từ TIA Portal)
        'datalog_data_ca',                  // Ca sản xuất
        'data_trang_thai',                  // Trạng thái hoạt động

        // Thông tin công nhân và sản phẩm
        'datalog_data_cn1',                 // Công nhân 1
        'datalog_data_cn2',                 // Công nhân 2
        'datalog_data_to',                  // Tổ sản xuất
        'datalog_data_ma_sp',               // Mã sản phẩm
        'datalog_data_nl1',                 // Nguyên liệu 1
        'datalog_data_nl2',                 // Nguyên liệu 2
        'datalog_data_nl_chi',              // Nguyên liệu chỉ
        'datalog_data_mau_chi',             // Màu chỉ
        'datalog_data_ma_dh',               // Mã đơn hàng
        'datalog_data_thn',                 // THN
        'datalog_data_tht',                 // THT
        'datalog_data_chuot',               // CHUOT
        'datalog_data_sth',                 // STH
        'datalog_data_ct',                  // Counter

        // Trạng thái hệ thống
        'dung_khan_cap',                    // Dừng khẩn cấp
        'den_bao_do',                       // Đèn báo đỏ
        'bao_loi_bien_tan_dong_co_vit_xoan',    // Báo lỗi biến tần động cơ vít xoắn
        'bao_loi_bien_tan_dong_co_may_chi',     // Báo lỗi biến tần động cơ máy chỉ
        'bao_loi_bien_tan_dong_co_dan_keo',     // Báo lỗi biến tần động cơ dàn kéo

        // Điều khiển động cơ
        'dieu_khien_dong_co_chinh',          // Điều khiển động cơ chính
        'dieu_khien_dong_co_may_chi',        // Điều khiển động cơ máy chỉ
        'dieu_khien_dan_keo_thuan',          // Điều khiển dàn kéo thuận
        'dieu_khien_dan_keo_nghich',         // Điều khiển dàn kéo nghịch
        'dieu_khien_bat_bien_tan_bom_ck',    // Điều khiển bật biến tần bơm CK

        // Trạng thái vùng nhiệt
        'bat_cl',                            // Bật CL
        'bat_xl_1',                          // Bật XL 1
        'bat_xl_2',
        'bat_xl_3',
        'bat_xl_4',
        'bat_xl_5',
        'bat_cn',                            // Bật CN

        // Trạng thái đầu hơi
        'bat_dh_1',                          // Bật ĐH 1
        'bat_dh_2',
        'bat_dh_3',
        'bat_dh_4',
        'bat_dh_5',
        'bat_dh_6',
        'bat_dh_7',
        'bat_dh_8',
        'bat_dh_9',
        'bat_dh_10',
        'bat_dh_11',
        'bat_dh_12',

        // Trạng thái xilanh máy chỉ
        'bat_tat_xl_may_chi_1',              // Bật tắt xl máy chỉ 1
        'bat_tat_xl_may_chi_2',
        'bat_tat_xl_may_chi_3',
        'bat_tat_xl_may_chi_4',

        // Thông số vận hành
        'bat_chay_dinh_luong_kg_h',          // Bật chạy định lượng kg/h
        'bat_chay_dinh_luong_g_m',           // Bật chạy định lượng g/m
        'dinh_luong_dat_g_m',                // Định lượng đặt g/m
        'tai_thuc_te_dc_chinh',              // Tải thực tế DC chính
        'toc_do_thuc_te_vx',                 // Tốc độ thực tế VX
        'toc_do_thuc_te_may_chi',            // Tốc độ thực tế máy chỉ
        'toc_do_thuc_te_dan_keo_m_p',        // Tốc độ thực tế dàn kéo
        'toc_do_dat_vx',                     // Tốc độ đặt VX
        'toc_do_dat_may_chi',                // Tốc độ đặt máy chỉ
        'toc_do_dat_dan_keo',                // Tốc độ đặt dàn kéo
        'nhiet_do_nhua',                     // Nhiệt độ nhựa
        'app_luc_nhua',                      // Áp lực nhựa
        'tai_thuc_te_may_chi',               // Tải thực tế máy chỉ
        'can_thuc_te',                       // Cân thực tế
        'nang_suatkg_h',                     // Năng suất(kg/h)
        'dinh_luong_g_m',                    // Định lượng g/m
        'hmi_toc_do_dat_bom_ck',             // HMI tốc độ đặt bơm CK

        // Nhiệt độ các vùng
        'nhiet_do_thuc_te_co_cl',            // Nhiệt độ thực tế cổ CL
        'nhiet_do_thuc_te_xl_1',             // Nhiệt độ thực tế XL 1
        'nhiet_do_thuc_te_xl_2',
        'nhiet_do_thuc_te_xl_3',
        'nhiet_do_thuc_te_xl_4',
        'nhiet_do_thuc_te_xl_5',
        'nhiet_do_thuc_te_cn',               // Nhiệt độ thực tế CN

        // Nhiệt độ đầu hơi
        'nhiet_do_thuc_te_dh_1',             // Nhiệt độ thực tế ĐH 1
        'nhiet_do_thuc_te_dh_2',
        'nhiet_do_thuc_te_dh_3',
        'nhiet_do_thuc_te_dh_4',
        'nhiet_do_thuc_te_dh_5',
        'nhiet_do_thuc_te_dh_6',
        'nhiet_do_thuc_te_dh_7',
        'nhiet_do_thuc_te_dh_8',
        'nhiet_do_thuc_te_dh_9',
        'nhiet_do_thuc_te_dh_10',
        'nhiet_do_thuc_te_dh_11',
        'nhiet_do_thuc_te_dh_12',

        // Nhiệt độ máy chỉ
        'nhiet_do_thuc_te_may_chi_xl_1',     // Nhiệt độ thực tế máy chỉ XL 1
        'nhiet_do_thuc_te_may_chi_xl_2',
        'nhiet_do_thuc_te_may_chi_xl_3',
        'nhiet_do_thuc_te_may_chi_xl_4',

        // Thông số vacuum và CK
        'nhiet_do_nuoc_vacuum_1',            // Nhiệt độ nước vacuum 1
        'nhiet_do_nuoc_vacuum_2',            // Nhiệt độ nước vacuum 2
        'ap_suat_be_ck1',                    // Áp suất bể CK1

        // Thời gian
        'datalog_data_gio_dung',             // Giờ dừng
        'datalog_data_gio_gia_nhiet',        // Giờ gia nhiệt
        'datalog_data_gio_chay_1',           // Giờ chạy 1
        'datalog_data_gio_chay_2',           // Giờ chạy 2
    ];

    protected $casts = [
        // Boolean fields
        'datalog_data_sth' => 'float',
        'datalog_data_ct' => 'float',
        'dung_khan_cap' => 'boolean',
        'den_bao_do' => 'boolean',
        'bao_loi_bien_tan_dong_co_vit_xoan' => 'boolean',
        'bao_loi_bien_tan_dong_co_may_chi' => 'boolean',
        'bao_loi_bien_tan_dong_co_dan_keo' => 'boolean',
        'dieu_khien_dong_co_chinh' => 'boolean',
        'dieu_khien_dong_co_may_chi' => 'boolean',
        'dieu_khien_dan_keo_thuan' => 'boolean',
        'dieu_khien_dan_keo_nghich' => 'boolean',
        'dieu_khien_bat_bien_tan_bom_ck' => 'boolean',
        'bat_cl' => 'boolean',
        'bat_xl_1' => 'boolean',
        'bat_xl_2' => 'boolean',
        'bat_xl_3' => 'boolean',
        'bat_xl_4' => 'boolean',
        'bat_xl_5' => 'boolean',
        'bat_cn' => 'boolean',
        'bat_dh_1' => 'boolean',
        'bat_dh_2' => 'boolean',
        'bat_dh_3' => 'boolean',
        'bat_dh_4' => 'boolean',
        'bat_dh_5' => 'boolean',
        'bat_dh_6' => 'boolean',
        'bat_dh_7' => 'boolean',
        'bat_dh_8' => 'boolean',
        'bat_dh_9' => 'boolean',
        'bat_dh_10' => 'boolean',
        'bat_dh_11' => 'boolean',
        'bat_dh_12' => 'boolean',
        'bat_tat_xl_may_chi_1' => 'boolean',
        'bat_tat_xl_may_chi_2' => 'boolean',
        'bat_tat_xl_may_chi_3' => 'boolean',
        'bat_tat_xl_may_chi_4' => 'boolean',
        'bat_chay_dinh_luong_kg_h' => 'boolean',
        'bat_chay_dinh_luong_g_m' => 'boolean',

        // Numeric fields
        'toc_do_thuc_te_vx' => 'float',
        'toc_do_thuc_te_may_chi' => 'float',
        'toc_do_thuc_te_dan_keo_m_p' => 'float',
        'toc_do_dat_vx' => 'float',
        'toc_do_dat_may_chi' => 'float',
        'toc_do_dat_dan_keo' => 'float',
        'app_luc_nhua' => 'float',
        'nhiet_do_nhua' => 'float',
        'tai_thuc_te_dc_chinh' => 'float',
        'tai_thuc_te_may_chi' => 'float',
        'can_thuc_te' => 'float',
        'nang_suatkg_h' => 'float',
        'dinh_luong_g_m' => 'float',
        'dinh_luong_dat_g_m' => 'float',
        'hmi_toc_do_dat_bom_ck' => 'float',

        // Temperature fields
        'nhiet_do_thuc_te_co_cl' => 'float',
        'nhiet_do_thuc_te_xl_1' => 'float',
        'nhiet_do_thuc_te_xl_2' => 'float',
        'nhiet_do_thuc_te_xl_3' => 'float',
        'nhiet_do_thuc_te_xl_4' => 'float',
        'nhiet_do_thuc_te_xl_5' => 'float',
        'nhiet_do_thuc_te_cn' => 'float',
        'nhiet_do_thuc_te_dh_1' => 'float',
        'nhiet_do_thuc_te_dh_2' => 'float',
        'nhiet_do_thuc_te_dh_3' => 'float',
        'nhiet_do_thuc_te_dh_4' => 'float',
        'nhiet_do_thuc_te_dh_5' => 'float',
        'nhiet_do_thuc_te_dh_6' => 'float',
        'nhiet_do_thuc_te_dh_7' => 'float',
        'nhiet_do_thuc_te_dh_8' => 'float',
        'nhiet_do_thuc_te_dh_9' => 'float',
        'nhiet_do_thuc_te_dh_10' => 'float',
        'nhiet_do_thuc_te_dh_11' => 'float',
        'nhiet_do_thuc_te_dh_12' => 'float',
        'nhiet_do_thuc_te_may_chi_xl_1' => 'float',
        'nhiet_do_thuc_te_may_chi_xl_2' => 'float',
        'nhiet_do_thuc_te_may_chi_xl_3' => 'float',
        'nhiet_do_thuc_te_may_chi_xl_4' => 'float',
        'nhiet_do_nuoc_vacuum_1' => 'float',
        'nhiet_do_nuoc_vacuum_2' => 'float',
        'ap_suat_be_ck1' => 'float',

        // Time fields
        'datalog_data_gio_dung' => 'integer',
        'datalog_data_gio_gia_nhiet' => 'integer',
        'datalog_data_gio_chay_1' => 'integer',
        'datalog_data_gio_chay_2' => 'integer',

        // Date fields
        'datalog_date' => 'date',
    ];

    public function machine()
    {
        return $this->belongsTo(Machine::class);
    }

    public function getTableStructure()
    {
        return [
            'Thông tin máy' => [
                'Mã máy' => ['field' => 'machine.code', 'format' => 'text'],
                'Ngày' => ['field' => 'datalog_date', 'format' => 'date'],
                'Ca' => ['field' => 'datalog_data_ca', 'format' => 'text'],
                'Trạng thái' => ['field' => 'data_trang_thai', 'format' => 'status']
            ],
            'Trạng thái hệ thống' => [
                'Dừng khẩn cấp' => ['field' => 'dung_khan_cap', 'format' => 'boolean'],
                'Đèn báo đỏ' => ['field' => 'den_bao_do', 'format' => 'boolean'],
                'Lỗi biến tần VX' => ['field' => 'bao_loi_bien_tan_dong_co_vit_xoan', 'format' => 'boolean'],
                'Lỗi biến tần máy chỉ' => ['field' => 'bao_loi_bien_tan_dong_co_may_chi', 'format' => 'boolean'],
                'Lỗi biến tần dàn kéo' => ['field' => 'bao_loi_bien_tan_dong_co_dan_keo', 'format' => 'boolean']
            ],
            'Điều khiển động cơ' => [
                'Động cơ chính' => ['field' => 'dieu_khien_dong_co_chinh', 'format' => 'boolean'],
                'Động cơ máy chỉ' => ['field' => 'dieu_khien_dong_co_may_chi', 'format' => 'boolean'],
                'Dàn kéo thuận' => ['field' => 'dieu_khien_dan_keo_thuan', 'format' => 'boolean'],
                'Dàn kéo nghịch' => ['field' => 'dieu_khien_dan_keo_nghich', 'format' => 'boolean'],
                'Bơm CK' => ['field' => 'dieu_khien_bat_bien_tan_bom_ck', 'format' => 'boolean']
            ],
            'Trạng thái vùng nhiệt' => [
                'Bật CL' => ['field' => 'bat_cl', 'format' => 'boolean'],
                'Bật XL 1' => ['field' => 'bat_xl_1', 'format' => 'boolean'],
                'Bật XL 2' => ['field' => 'bat_xl_2', 'format' => 'boolean'],
                'Bật XL 3' => ['field' => 'bat_xl_3', 'format' => 'boolean'],
                'Bật XL 4' => ['field' => 'bat_xl_4', 'format' => 'boolean'],
                'Bật XL 5' => ['field' => 'bat_xl_5', 'format' => 'boolean'],
                'Bật CN' => ['field' => 'bat_cn', 'format' => 'boolean']
            ],
            'Trạng thái đầu hơi' => [
                'Bật ĐH 1' => ['field' => 'bat_dh_1', 'format' => 'boolean'],
                'Bật ĐH 2' => ['field' => 'bat_dh_2', 'format' => 'boolean'],
                'Bật ĐH 3' => ['field' => 'bat_dh_3', 'format' => 'boolean'],
                'Bật ĐH 4' => ['field' => 'bat_dh_4', 'format' => 'boolean'],
                'Bật ĐH 5' => ['field' => 'bat_dh_5', 'format' => 'boolean'],
                'Bật ĐH 6' => ['field' => 'bat_dh_6', 'format' => 'boolean'],
                'Bật ĐH 7' => ['field' => 'bat_dh_7', 'format' => 'boolean'],
                'Bật ĐH 8' => ['field' => 'bat_dh_8', 'format' => 'boolean'],
                'Bật ĐH 9' => ['field' => 'bat_dh_9', 'format' => 'boolean'],
                'Bật ĐH 10' => ['field' => 'bat_dh_10', 'format' => 'boolean'],
                'Bật ĐH 11' => ['field' => 'bat_dh_11', 'format' => 'boolean'],
                'Bật ĐH 12' => ['field' => 'bat_dh_12', 'format' => 'boolean']
            ],
            'Trạng thái xilanh máy chỉ' => [
                'XL máy chỉ 1' => ['field' => 'bat_tat_xl_may_chi_1', 'format' => 'boolean'],
                'XL máy chỉ 2' => ['field' => 'bat_tat_xl_may_chi_2', 'format' => 'boolean'],
                'XL máy chỉ 3' => ['field' => 'bat_tat_xl_may_chi_3', 'format' => 'boolean'],
                'XL máy chỉ 4' => ['field' => 'bat_tat_xl_may_chi_4', 'format' => 'boolean']
            ],
            'Thông số vận hành' => [
                'Chạy định lượng kg/h' => ['field' => 'bat_chay_dinh_luong_kg_h', 'format' => 'boolean'],
                'Chạy định lượng g/m' => ['field' => 'bat_chay_dinh_luong_g_m', 'format' => 'boolean'],
                'Định lượng đặt g/m' => ['field' => 'dinh_luong_dat_g_m', 'format' => 'number', 'unit' => 'g/m'],
                'Tải thực tế DC chính' => ['field' => 'tai_thuc_te_dc_chinh', 'format' => 'number', 'unit' => '%'],
                'Tốc độ thực tế VX' => ['field' => 'toc_do_thuc_te_vx', 'format' => 'number', 'unit' => 'm/phút'],
                'Tốc độ thực tế máy chỉ' => ['field' => 'toc_do_thuc_te_may_chi', 'format' => 'number', 'unit' => 'm/phút'],
                'Tốc độ thực tế dàn kéo' => ['field' => 'toc_do_thuc_te_dan_keo_m_p', 'format' => 'number', 'unit' => 'm/phút'],
                'Tốc độ đặt VX' => ['field' => 'toc_do_dat_vx', 'format' => 'number', 'unit' => 'm/phút'],
                'Tốc độ đặt máy chỉ' => ['field' => 'toc_do_dat_may_chi', 'format' => 'number', 'unit' => 'm/phút'],
                'Tốc độ đặt dàn kéo' => ['field' => 'toc_do_dat_dan_keo', 'format' => 'number', 'unit' => 'm/phút'],
                'Tốc độ đặt bơm CK' => ['field' => 'hmi_toc_do_dat_bom_ck', 'format' => 'number', 'unit' => '%']
            ],
            'Thông số nhiệt độ & áp lực' => [
                'Nhiệt độ nhựa' => ['field' => 'nhiet_do_nhua', 'format' => 'number', 'unit' => '°C'],
                'Áp lực nhựa' => ['field' => 'app_luc_nhua', 'format' => 'number', 'unit' => 'bar'],
                'Tải thực tế máy chỉ' => ['field' => 'tai_thuc_te_may_chi', 'format' => 'number', 'unit' => '%'],
                'Cân thực tế' => ['field' => 'can_thuc_te', 'format' => 'number', 'unit' => 'kg'],
                'Năng suất' => ['field' => 'nang_suatkg_h', 'format' => 'number', 'unit' => 'kg/h'],
                'Định lượng g/m' => ['field' => 'dinh_luong_g_m', 'format' => 'number', 'unit' => 'g/m']
            ],
            'Nhiệt độ các vùng' => [
                'Nhiệt độ cổ CL' => ['field' => 'nhiet_do_thuc_te_co_cl', 'format' => 'number', 'unit' => '°C'],
                'Nhiệt độ XL 1' => ['field' => 'nhiet_do_thuc_te_xl_1', 'format' => 'number', 'unit' => '°C'],
                'Nhiệt độ XL 2' => ['field' => 'nhiet_do_thuc_te_xl_2', 'format' => 'number', 'unit' => '°C'],
                'Nhiệt độ XL 3' => ['field' => 'nhiet_do_thuc_te_xl_3', 'format' => 'number', 'unit' => '°C'],
                'Nhiệt độ XL 4' => ['field' => 'nhiet_do_thuc_te_xl_4', 'format' => 'number', 'unit' => '°C'],
                'Nhiệt độ XL 5' => ['field' => 'nhiet_do_thuc_te_xl_5', 'format' => 'number', 'unit' => '°C'],
                'Nhiệt độ CN' => ['field' => 'nhiet_do_thuc_te_cn', 'format' => 'number', 'unit' => '°C']
            ],
            'Nhiệt độ đầu hơi' => [
                'Nhiệt độ ĐH 1' => ['field' => 'nhiet_do_thuc_te_dh_1', 'format' => 'number', 'unit' => '°C'],
                'Nhiệt độ ĐH 2' => ['field' => 'nhiet_do_thuc_te_dh_2', 'format' => 'number', 'unit' => '°C'],
                'Nhiệt độ ĐH 3' => ['field' => 'nhiet_do_thuc_te_dh_3', 'format' => 'number', 'unit' => '°C'],
                'Nhiệt độ ĐH 4' => ['field' => 'nhiet_do_thuc_te_dh_4', 'format' => 'number', 'unit' => '°C'],
                'Nhiệt độ ĐH 5' => ['field' => 'nhiet_do_thuc_te_dh_5', 'format' => 'number', 'unit' => '°C'],
                'Nhiệt độ ĐH 6' => ['field' => 'nhiet_do_thuc_te_dh_6', 'format' => 'number', 'unit' => '°C'],
                'Nhiệt độ ĐH 7' => ['field' => 'nhiet_do_thuc_te_dh_7', 'format' => 'number', 'unit' => '°C'],
                'Nhiệt độ ĐH 8' => ['field' => 'nhiet_do_thuc_te_dh_8', 'format' => 'number', 'unit' => '°C'],
                'Nhiệt độ ĐH 9' => ['field' => 'nhiet_do_thuc_te_dh_9', 'format' => 'number', 'unit' => '°C'],
                'Nhiệt độ ĐH 10' => ['field' => 'nhiet_do_thuc_te_dh_10', 'format' => 'number', 'unit' => '°C'],
                'Nhiệt độ ĐH 11' => ['field' => 'nhiet_do_thuc_te_dh_11', 'format' => 'number', 'unit' => '°C'],
                'Nhiệt độ ĐH 12' => ['field' => 'nhiet_do_thuc_te_dh_12', 'format' => 'number', 'unit' => '°C']
            ],
            'Nhiệt độ máy chỉ' => [
                'Nhiệt độ máy chỉ XL 1' => ['field' => 'nhiet_do_thuc_te_may_chi_xl_1', 'format' => 'number', 'unit' => '°C'],
                'Nhiệt độ máy chỉ XL 2' => ['field' => 'nhiet_do_thuc_te_may_chi_xl_2', 'format' => 'number', 'unit' => '°C'],
                'Nhiệt độ máy chỉ XL 3' => ['field' => 'nhiet_do_thuc_te_may_chi_xl_3', 'format' => 'number', 'unit' => '°C'],
                'Nhiệt độ máy chỉ XL 4' => ['field' => 'nhiet_do_thuc_te_may_chi_xl_4', 'format' => 'number', 'unit' => '°C']
            ],
            'Thông số khác' => [
                'Nhiệt độ nước vacuum 1' => ['field' => 'nhiet_do_nuoc_vacuum_1', 'format' => 'number', 'unit' => '°C'],
                'Nhiệt độ nước vacuum 2' => ['field' => 'nhiet_do_nuoc_vacuum_2', 'format' => 'number', 'unit' => '°C'],
                'Áp suất bể CK' => ['field' => 'ap_suat_be_ck1', 'format' => 'number', 'unit' => 'bar']
            ],
            'Thông tin sản xuất' => [
                'Công nhân 1' => ['field' => 'datalog_data_cn1', 'format' => 'text'],
                'Công nhân 2' => ['field' => 'datalog_data_cn2', 'format' => 'text'],
                'Tổ sản xuất' => ['field' => 'datalog_data_to', 'format' => 'text'],
                'Mã sản phẩm' => ['field' => 'datalog_data_ma_sp', 'format' => 'text'],
                'Nguyên liệu 1' => ['field' => 'datalog_data_nl1', 'format' => 'text'],
                'Nguyên liệu 2' => ['field' => 'datalog_data_nl2', 'format' => 'text'],
                'Nguyên liệu chỉ' => ['field' => 'datalog_data_nl_chi', 'format' => 'text'],
                'Màu chỉ' => ['field' => 'datalog_data_mau_chi', 'format' => 'text'],
                'Mã đơn hàng' => ['field' => 'datalog_data_ma_dh', 'format' => 'text'],
                'THN' => ['field' => 'datalog_data_thn', 'format' => 'text'],
                'THT' => ['field' => 'datalog_data_tht', 'format' => 'text'],
                'CHUOT' => ['field' => 'datalog_data_chuot', 'format' => 'text'],
                'STH' => ['field' => 'datalog_data_sth', 'format' => 'number', 'unit' => ''],
                'Counter' => ['field' => 'datalog_data_ct', 'format' => 'number', 'unit' => '']
            ],
            'Thời gian' => [
                'Giờ dừng' => ['field' => 'datalog_data_gio_dung', 'format' => 'number', 'unit' => 'giờ'],
                'Giờ gia nhiệt' => ['field' => 'datalog_data_gio_gia_nhiet', 'format' => 'number', 'unit' => 'giờ'],
                'Giờ chạy 1' => ['field' => 'datalog_data_gio_chay_1', 'format' => 'number', 'unit' => 'giờ'],
                'Giờ chạy 2' => ['field' => 'datalog_data_gio_chay_2', 'format' => 'number', 'unit' => 'giờ']
            ]
        ];
    }

    public function formatValue($value, $format, $unit = '')
    {
        if ($value === null) return '-';

        switch ($format) {
            case 'number':
                return number_format($value, 2) . ($unit ? " $unit" : '');
            case 'date':
                return date('d/m/Y', strtotime($value));
            case 'boolean':
                return $value ? '<span class="badge badge-success">ON</span>' :
                            '<span class="badge badge-danger">OFF</span>';
            case 'status':
                $class = strtolower($value) == 'dang sx' ? 'success' : 'danger';
                return "<span class='badge badge-$class'>$value</span>";
            default:
                return $value;
        }
    }

    /**
     * Trả về danh sách các trường có thể cấu hình cảnh báo
     */
    protected static $alertableColumns = [
        'boolean' => [
            // Trạng thái hệ thống
            'dung_khan_cap' => 'Dừng khẩn cấp',
            'den_bao_do' => 'Đèn báo đỏ',
            'bao_loi_bien_tan_dong_co_vit_xoan' => 'Lỗi biến tần động cơ vít xoắn',
            'bao_loi_bien_tan_dong_co_may_chi' => 'Lỗi biến tần máy chỉ',
            'bao_loi_bien_tan_dong_co_dan_keo' => 'Lỗi biến tần dàn kéo',
            'dieu_khien_dong_co_chinh' => 'Điều khiển động cơ chính',
            'dieu_khien_dong_co_may_chi' => 'Điều khiển động cơ máy chỉ',
            'dieu_khien_dan_keo_thuan' => 'Điều khiển dàn kéo thuận',
            'dieu_khien_dan_keo_nghich' => 'Điều khiển dàn kéo nghịch',
            'dieu_khien_bat_bien_tan_bom_ck' => 'Điều khiển bật biến tần bơm CK',
            'bat_cl' => 'Bật CL',
            'bat_xl_1' => 'Bật XL 1',
            'bat_xl_2' => 'Bật XL 2',
            'bat_xl_3' => 'Bật XL 3',
            'bat_xl_4' => 'Bật XL 4',
            'bat_xl_5' => 'Bật XL 5',
            'bat_cn' => 'Bật CN',
            'bat_dh_1' => 'Bật ĐH 1',
            'bat_dh_2' => 'Bật ĐH 2',
            'bat_dh_3' => 'Bật ĐH 3',
            'bat_dh_4' => 'Bật ĐH 4',
            'bat_dh_5' => 'Bật ĐH 5',
            'bat_dh_6' => 'Bật ĐH 6',
            'bat_dh_7' => 'Bật ĐH 7',
            'bat_dh_8' => 'Bật ĐH 8',
            'bat_dh_9' => 'Bật ĐH 9',
            'bat_dh_10' => 'Bật ĐH 10',
            'bat_dh_11' => 'Bật ĐH 11',
            'bat_dh_12' => 'Bật ĐH 12',
            'bat_tat_xl_may_chi_1' => 'Bật tắt xl máy chỉ 1',
            'bat_tat_xl_may_chi_2' => 'Bật tắt xl máy chỉ 2',
            'bat_tat_xl_may_chi_3' => 'Bật tắt xl máy chỉ 3',
            'bat_tat_xl_may_chi_4' => 'Bật tắt xl máy chỉ 4',
            'bat_chay_dinh_luong_kg_h' => 'Bật chạy định lượng kg/h',
            'bat_chay_dinh_luong_g_m' => 'Bật chạy định lượng g/m'
        ],
        'parameter' => [
            // Thông số vận hành
            'toc_do_thuc_te_vx' => 'Tốc độ thực tế VX',
            'toc_do_thuc_te_may_chi' => 'Tốc độ thực tế máy chỉ',
            'toc_do_thuc_te_dan_keo_m_p' => 'Tốc độ thực tế dàn kéo',
            'toc_do_dat_vx' => 'Tốc độ đặt VX',
            'toc_do_dat_may_chi' => 'Tốc độ đặt máy chỉ',
            'toc_do_dat_dan_keo' => 'Tốc độ đặt dàn kéo',
            'tai_thuc_te_dc_chinh' => 'Tải thực tế DC chính',
            'tai_thuc_te_may_chi' => 'Tải thực tế máy chỉ',
            'can_thuc_te' => 'Cân thực tế',
            'nang_suatkg_h' => 'Năng suất',
            'dinh_luong_g_m' => 'Định lượng g/m',
            'dinh_luong_dat_g_m' => 'Định lượng đặt g/m',
            'hmi_toc_do_dat_bom_ck' => 'Tốc độ đặt bơm CK',
            'nhiet_do_nhua' => 'Nhiệt độ nhựa',
            'app_luc_nhua' => 'Áp lực nhựa',
            'nhiet_do_thuc_te_co_cl' => 'Nhiệt độ cổ CL',
            'nhiet_do_thuc_te_xl_1' => 'Nhiệt độ XL 1',
            'nhiet_do_thuc_te_xl_2' => 'Nhiệt độ XL 2',
            'nhiet_do_thuc_te_xl_3' => 'Nhiệt độ XL 3',
            'nhiet_do_thuc_te_xl_4' => 'Nhiệt độ XL 4',
            'nhiet_do_thuc_te_xl_5' => 'Nhiệt độ XL 5',
            'nhiet_do_thuc_te_cn' => 'Nhiệt độ CN',
            'nhiet_do_thuc_te_dh_1' => 'Nhiệt độ ĐH 1',
            'nhiet_do_thuc_te_dh_2' => 'Nhiệt độ ĐH 2',
            'nhiet_do_thuc_te_dh_3' => 'Nhiệt độ ĐH 3',
            'nhiet_do_thuc_te_dh_4' => 'Nhiệt độ ĐH 4',
            'nhiet_do_thuc_te_dh_5' => 'Nhiệt độ ĐH 5',
            'nhiet_do_thuc_te_dh_6' => 'Nhiệt độ ĐH 6',
            'nhiet_do_thuc_te_dh_7' => 'Nhiệt độ ĐH 7',
            'nhiet_do_thuc_te_dh_8' => 'Nhiệt độ ĐH 8',
            'nhiet_do_thuc_te_dh_9' => 'Nhiệt độ ĐH 9',
            'nhiet_do_thuc_te_dh_10' => 'Nhiệt độ ĐH 10',
            'nhiet_do_thuc_te_dh_11' => 'Nhiệt độ ĐH 11',
            'nhiet_do_thuc_te_dh_12' => 'Nhiệt độ ĐH 12',
            'nhiet_do_thuc_te_may_chi_xl_1' => 'Nhiệt độ máy chỉ XL 1',
            'nhiet_do_thuc_te_may_chi_xl_2' => 'Nhiệt độ máy chỉ XL 2',
            'nhiet_do_thuc_te_may_chi_xl_3' => 'Nhiệt độ máy chỉ XL 3',
            'nhiet_do_thuc_te_may_chi_xl_4' => 'Nhiệt độ máy chỉ XL 4',
            'nhiet_do_nuoc_vacuum_1' => 'Nhiệt độ nước vacuum 1',
            'nhiet_do_nuoc_vacuum_2' => 'Nhiệt độ nước vacuum 2',
            'ap_suat_be_ck1' => 'Áp suất bể CK'
        ]
    ];

    public static function getAlertableColumns()
    {
        return [
            'boolean' => collect(self::$alertableColumns['boolean'])
                ->map(fn($label, $key) => [
                    'key' => $key,
                    'label' => $label,
                    'type' => 'boolean'
                ]),
            'parameter' => collect(self::$alertableColumns['parameter'])
                ->map(fn($label, $key) => [
                    'key' => $key,
                    'label' => $label,
                    'type' => 'parameter'
                ])
        ];
    }

}
