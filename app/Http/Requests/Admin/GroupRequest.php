<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\FormRequest;
use Illuminate\Validation\Rule;

class GroupRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $requiredIfReview = function ($driver) {
            return Rule::requiredIf($this->input('configs.is_enable_scan') && $this->input('configs.scan_configs.driver') === $driver);
        };

        $requiredIfWatermark = function ($driver) {
            return Rule::requiredIf($this->input('configs.is_enable_watermark') && $this->input('configs.watermark_configs.driver') === $driver);
        };

        return [
            'name' => 'required|between:2,30',
            'is_default' => 'boolean',
            'is_guest' => 'boolean',
            'configs' => 'required|array',
            'configs.maximum_file_size' => 'required|numeric',
            'configs.concurrent_upload_num' => 'required|integer',
            'configs.limit_per_minute' => 'required|integer',
            'configs.limit_per_hour' => 'required|integer',
            'configs.limit_per_day' => 'required|integer',
            'configs.limit_per_week' => 'required|integer',
            'configs.limit_per_month' => 'required|integer',
            'configs.image_save_quality' => 'required|min:1|max:100',
            'configs.image_save_format' => '',
            'configs.path_naming_rule' => 'max:400',
            'configs.file_naming_rule' => 'max:400',
            'configs.accepted_file_suffixes' => 'required|array|in:jpeg,jpg,png,gif,tif,bmp,ico,psd,webp',

            'configs.is_enable_scan' => 'boolean',
            'configs.scanned_action' => [
                'exclude_if:configs.is_enable_scan,false',
                'in:mark,delete',
            ],
            'configs.scan_configs.driver' => ['exclude_if:configs.is_enable_scan,false', 'in:tencent,aliyun,nsfwjs'],
            'configs.scan_configs.drivers.tencent.endpoint' => [$requiredIfReview('tencent')],
            'configs.scan_configs.drivers.tencent.secret_id' => [$requiredIfReview('tencent')],
            'configs.scan_configs.drivers.tencent.secret_key' => [$requiredIfReview('tencent')],
            'configs.scan_configs.drivers.tencent.region' => [$requiredIfReview('tencent')],
            'configs.scan_configs.drivers.tencent.biz_type' => '',

            'configs.scan_configs.drivers.aliyun.access_key_id' => [$requiredIfReview('aliyun')],
            'configs.scan_configs.drivers.aliyun.access_key_secret' => [$requiredIfReview('aliyun')],
            'configs.scan_configs.drivers.aliyun.region_id' => [$requiredIfReview('aliyun')],
            'configs.scan_configs.drivers.aliyun.biz_type' => '',
            'configs.scan_configs.drivers.aliyun.scenes' => [$requiredIfReview('aliyun'), 'array'],

            'configs.scan_configs.drivers.nsfwjs.api_url' => [$requiredIfReview('nsfwjs')],
            'configs.scan_configs.drivers.nsfwjs.attr_name' => [$requiredIfReview('nsfwjs'), 'nullable'],
            'configs.scan_configs.drivers.nsfwjs.threshold' => [$requiredIfReview('nsfwjs'), 'nullable', 'integer', 'between:1,100'],

            'configs.is_enable_original_protection' => 'boolean',
            'configs.image_cache_ttl' => 'nullable|numeric',

            'configs.is_enable_watermark' => 'boolean',
            'configs.watermark_configs.mode' => ['in:1,2'],
            'configs.watermark_configs.driver' => ['exclude_if:configs.is_enable_watermark,false', 'in:font,image'],
            'configs.watermark_configs.drivers.font.font' => [
                $requiredIfWatermark('font'),
                function ($attribute, $value, $fail) {
                    if (! file_exists(storage_path('app/public/'.$value))) {
                        $fail('?????????????????????');
                    }
                },
            ],
            'configs.watermark_configs.drivers.font.position' => [$requiredIfWatermark('font')],
            'configs.watermark_configs.drivers.font.text' => [$requiredIfWatermark('font')],
            'configs.watermark_configs.drivers.font.color' => [$requiredIfWatermark('font')],
            'configs.watermark_configs.drivers.font.size' => [$requiredIfWatermark('font'), 'nullable', 'integer'],
            'configs.watermark_configs.drivers.font.angle' => [$requiredIfWatermark('font'), 'nullable', 'integer'],
            'configs.watermark_configs.drivers.font.x' => [$requiredIfWatermark('font'), 'nullable', 'integer'],
            'configs.watermark_configs.drivers.font.y' => [$requiredIfWatermark('font'), 'nullable', 'integer'],
            'configs.watermark_configs.drivers.image.image' => [
                $requiredIfWatermark('image'),
                function ($attribute, $value, $fail) {
                    if (! file_exists(storage_path('app/public/'.$value))) {
                        $fail('?????????????????????');
                    }
                },
            ],
            'configs.watermark_configs.drivers.image.position' => [$requiredIfWatermark('image')],
            'configs.watermark_configs.drivers.image.width' => [$requiredIfWatermark('image'), 'nullable', 'integer'],
            'configs.watermark_configs.drivers.image.height' => [$requiredIfWatermark('image'), 'nullable', 'integer'],
            'configs.watermark_configs.drivers.image.opacity' => [
                $requiredIfWatermark('image'), 'nullable', 'integer', 'between:0,100',
            ],
            'configs.watermark_configs.drivers.image.rotate' => [$requiredIfWatermark('image'), 'nullable', 'integer'],
            'configs.watermark_configs.drivers.image.x' => [$requiredIfWatermark('image'), 'nullable', 'integer'],
            'configs.watermark_configs.drivers.image.y' => [$requiredIfWatermark('image'), 'nullable', 'integer'],
        ];
    }

    public function attributes()
    {
        return [
            'name' => '?????????',
            'is_default' => '????????????',
            'configs' => '?????????',
            'configs.maximum_file_size' => '????????????????????????',
            'configs.concurrent_upload_num' => '??????????????????',
            'configs.limit_per_minute' => '?????????????????????',
            'configs.limit_per_hour' => '?????????????????????',
            'configs.limit_per_day' => '??????????????????',
            'configs.limit_per_week' => '??????????????????',
            'configs.limit_per_month' => '??????????????????',
            'configs.path_naming_rule' => '??????????????????',
            'configs.file_naming_rule' => '??????????????????',
            'configs.image_save_quality' => '??????????????????',
            'configs.image_save_format' => '??????????????????',
            'configs.accepted_file_suffixes' => '???????????????????????????',

            'configs.is_enable_scan' => '????????????????????????',
            'configs.scanned_action' => '??????????????????',
            'configs.scan_configs.driver' => '??????????????????',
            'configs.scan_configs.drivers.tencent.endpoint' => 'Endpoint',
            'configs.scan_configs.drivers.tencent.secret_id' => 'SecretId',
            'configs.scan_configs.drivers.tencent.secret_key' => 'SecretKey',
            'configs.scan_configs.drivers.tencent.region' => '????????????',
            'configs.scan_configs.drivers.tencent.biz_type' => '????????????',
            'configs.scan_configs.drivers.aliyun.access_key_id' => 'AccessKeyId',
            'configs.scan_configs.drivers.aliyun.access_key_secret' => 'AccessKeySecret',
            'configs.scan_configs.drivers.aliyun.region_id' => '????????????',
            'configs.scan_configs.drivers.aliyun.biz_type' => '????????????',
            'configs.scan_configs.drivers.aliyun.scenes' => '????????????',
            'configs.scan_configs.drivers.nsfwjs.api_url' => '????????????',
            'configs.scan_configs.drivers.nsfwjs.attr_name' => '????????????',
            'configs.scan_configs.drivers.nsfwjs.threshold' => '??????',

            'configs.is_enable_original_protection' => '??????????????????????????????',
            'configs.image_cache_ttl' => '??????????????????',

            'configs.is_enable_watermark' => '????????????????????????',
            'configs.watermark_configs.driver' => '????????????',
            'configs.watermark_configs.drivers.font.font' => '????????????',
            'configs.watermark_configs.drivers.font.position' => '????????????',
            'configs.watermark_configs.drivers.font.text' => '????????????',
            'configs.watermark_configs.drivers.font.color' => '????????????',
            'configs.watermark_configs.drivers.font.size' => '??????????????????',
            'configs.watermark_configs.drivers.font.angle' => '??????????????????',
            'configs.watermark_configs.drivers.font.x' => '??????X????????????',
            'configs.watermark_configs.drivers.font.y' => '??????Y????????????',
            'configs.watermark_configs.drivers.image.image' => '??????????????????',
            'configs.watermark_configs.drivers.image.position' => '????????????',
            'configs.watermark_configs.drivers.image.width' => '??????????????????',
            'configs.watermark_configs.drivers.image.height' => '??????????????????',
            'configs.watermark_configs.drivers.image.opacity' => '???????????????',
            'configs.watermark_configs.drivers.image.rotate' => '??????????????????',
            'configs.watermark_configs.drivers.image.x' => '??????X????????????',
            'configs.watermark_configs.drivers.image.y' => '??????Y????????????',
        ];
    }
}
