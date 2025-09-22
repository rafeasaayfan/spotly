<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | الأسطر التالية تحتوي على رسائل الخطأ الافتراضية المستخدمة من قبل
    | صنف المدقق. بعض هذه القواعد تحتوي على عدة نسخ مثل قواعد الحجم.
    | يمكنك تعديل أي من هذه الرسائل بما يتناسب مع تطبيقك.
    |
    */

    'accepted' => 'حقل :attribute يجب أن يكون مقبولًا.',
    'accepted_if' => 'حقل :attribute يجب أن يكون مقبولًا عندما يكون :other يساوي :value.',
    'active_url' => 'حقل :attribute يجب أن يكون رابطًا صحيحًا.',
    'after' => 'حقل :attribute يجب أن يكون تاريخًا بعد :date.',
    'after_or_equal' => 'حقل :attribute يجب أن يكون تاريخًا بعد أو يساوي :date.',
    'alpha' => 'حقل :attribute يجب أن يحتوي على حروف فقط.',
    'alpha_dash' => 'حقل :attribute يجب أن يحتوي فقط على حروف، أرقام، شرطات وشرطات سفلية.',
    'alpha_num' => 'حقل :attribute يجب أن يحتوي فقط على حروف وأرقام.',
    'any_of' => 'حقل :attribute غير صالح.',
    'array' => 'حقل :attribute يجب أن يكون مصفوفة.',
    'ascii' => 'حقل :attribute يجب أن يحتوي فقط على رموز وأحرف أبجدية رقمية أحادية البايت.',
    'before' => 'حقل :attribute يجب أن يكون تاريخًا قبل :date.',
    'before_or_equal' => 'حقل :attribute يجب أن يكون تاريخًا قبل أو يساوي :date.',
    'between' => [
        'array' => 'حقل :attribute يجب أن يحتوي بين :min و :max عناصر.',
        'file' => 'حجم الملف في حقل :attribute يجب أن يكون بين :min و :max كيلوبايت.',
        'numeric' => 'حقل :attribute يجب أن يكون بين :min و :max.',
        'string' => 'حقل :attribute يجب أن يحتوي بين :min و :max حروف.',
    ],
    'boolean' => 'حقل :attribute يجب أن يكون صحيحًا أو خطأ.',
    'can' => 'حقل :attribute يحتوي على قيمة غير مسموح بها.',
    'confirmed' => 'تأكيد حقل :attribute لا يتطابق.',
    'contains' => 'حقل :attribute ينقصه قيمة مطلوبة.',
    'current_password' => 'كلمة المرور غير صحيحة.',
    'date' => 'حقل :attribute يجب أن يكون تاريخًا صحيحًا.',
    'date_equals' => 'حقل :attribute يجب أن يكون تاريخًا مساويًا لـ :date.',
    'date_format' => 'حقل :attribute لا يطابق الصيغة :format.',
    'decimal' => 'حقل :attribute يجب أن يحتوي على :decimal منازل عشرية.',
    'declined' => 'حقل :attribute يجب أن يكون مرفوضًا.',
    'declined_if' => 'حقل :attribute يجب أن يكون مرفوضًا عندما يكون :other يساوي :value.',
    'different' => 'حقل :attribute و :other يجب أن يكونا مختلفين.',
    'digits' => 'حقل :attribute يجب أن يكون :digits أرقام.',
    'digits_between' => 'حقل :attribute يجب أن يكون بين :min و :max أرقام.',
    'dimensions' => 'حقل :attribute يحتوي على أبعاد صورة غير صالحة.',
    'distinct' => 'حقل :attribute يحتوي على قيمة مكررة.',
    'doesnt_end_with' => 'حقل :attribute يجب ألا ينتهي بأحد القيم التالية: :values.',
    'doesnt_start_with' => 'حقل :attribute يجب ألا يبدأ بأحد القيم التالية: :values.',
    'email' => 'حقل :attribute يجب أن يكون بريدًا إلكترونيًا صحيحًا.',
    'ends_with' => 'حقل :attribute يجب أن ينتهي بأحد القيم التالية: :values.',
    'enum' => 'القيمة المختارة في :attribute غير صالحة.',
    'exists' => 'القيمة المختارة في :attribute غير صالحة.',
    'extensions' => 'حقل :attribute يجب أن يحتوي على امتداد من القائمة التالية: :values.',
    'file' => 'حقل :attribute يجب أن يكون ملفًا.',
    'filled' => 'حقل :attribute يجب أن يحتوي على قيمة.',
    'gt' => [
        'array' => 'حقل :attribute يجب أن يحتوي أكثر من :value عناصر.',
        'file' => 'حجم الملف في حقل :attribute يجب أن يكون أكبر من :value كيلوبايت.',
        'numeric' => 'حقل :attribute يجب أن يكون أكبر من :value.',
        'string' => 'حقل :attribute يجب أن يحتوي على أكثر من :value حروف.',
    ],
    'gte' => [
        'array' => 'حقل :attribute يجب أن يحتوي على :value عناصر أو أكثر.',
        'file' => 'حجم الملف في حقل :attribute يجب أن يكون أكبر من أو يساوي :value كيلوبايت.',
        'numeric' => 'حقل :attribute يجب أن يكون أكبر من أو يساوي :value.',
        'string' => 'حقل :attribute يجب أن يحتوي على :value حروف أو أكثر.',
    ],
    'hex_color' => 'حقل :attribute يجب أن يكون لونًا سداسيًا صحيحًا.',
    'image' => 'حقل :attribute يجب أن يكون صورة.',
    'in' => 'القيمة المختارة في :attribute غير صالحة.',
    'in_array' => 'حقل :attribute يجب أن يوجد في :other.',
    'integer' => 'حقل :attribute يجب أن يكون عددًا صحيحًا.',
    'ip' => 'حقل :attribute يجب أن يكون عنوان IP صحيحًا.',
    'ipv4' => 'حقل :attribute يجب أن يكون عنوان IPv4 صحيحًا.',
    'ipv6' => 'حقل :attribute يجب أن يكون عنوان IPv6 صحيحًا.',
    'json' => 'حقل :attribute يجب أن يكون نص JSON صحيح.',
    'list' => 'حقل :attribute يجب أن يكون قائمة.',
    'lowercase' => 'حقل :attribute يجب أن يكون بأحرف صغيرة.',
    'lt' => [
        'array' => 'حقل :attribute يجب أن يحتوي أقل من :value عناصر.',
        'file' => 'حجم الملف في حقل :attribute يجب أن يكون أصغر من :value كيلوبايت.',
        'numeric' => 'حقل :attribute يجب أن يكون أصغر من :value.',
        'string' => 'حقل :attribute يجب أن يحتوي أقل من :value حروف.',
    ],
    'lte' => [
        'array' => 'حقل :attribute يجب ألا يحتوي على أكثر من :value عناصر.',
        'file' => 'حجم الملف في حقل :attribute يجب أن يكون أصغر من أو يساوي :value كيلوبايت.',
        'numeric' => 'حقل :attribute يجب أن يكون أصغر من أو يساوي :value.',
        'string' => 'حقل :attribute يجب أن يحتوي :value حروف أو أقل.',
    ],
    'mac_address' => 'حقل :attribute يجب أن يكون عنوان MAC صحيح.',
    'max' => [
        'array' => 'حقل :attribute يجب ألا يحتوي أكثر من :max عناصر.',
        'file' => 'حجم الملف في حقل :attribute يجب ألا يكون أكبر من :max كيلوبايت.',
        'numeric' => 'حقل :attribute يجب ألا يكون أكبر من :max.',
        'string' => 'حقل :attribute يجب ألا يحتوي أكثر من :max حروف.',
    ],
    'max_digits' => 'حقل :attribute يجب ألا يحتوي أكثر من :max أرقام.',
    'mimes' => 'حقل :attribute يجب أن يكون ملفًا من نوع: :values.',
    'mimetypes' => 'حقل :attribute يجب أن يكون ملفًا من نوع: :values.',
    'min' => [
        'array' => 'حقل :attribute يجب أن يحتوي على الأقل :min عناصر.',
        'file' => 'حجم الملف في حقل :attribute يجب أن يكون على الأقل :min كيلوبايت.',
        'numeric' => 'حقل :attribute يجب أن يكون على الأقل :min.',
        'string' => 'حقل :attribute يجب أن يحتوي على الأقل :min حروف.',
    ],
    'min_digits' => 'حقل :attribute يجب أن يحتوي على الأقل :min أرقام.',
    'missing' => 'حقل :attribute يجب أن يكون مفقودًا.',
    'missing_if' => 'حقل :attribute يجب أن يكون مفقودًا عندما يكون :other يساوي :value.',
    'missing_unless' => 'حقل :attribute يجب أن يكون مفقودًا ما لم يكن :other يساوي :value.',
    'missing_with' => 'حقل :attribute يجب أن يكون مفقودًا عند وجود :values.',
    'missing_with_all' => 'حقل :attribute يجب أن يكون مفقودًا عند وجود :values.',
    'multiple_of' => 'حقل :attribute يجب أن يكون مضاعفًا لـ :value.',
    'not_in' => 'القيمة المختارة في :attribute غير صالحة.',
    'not_regex' => 'صيغة حقل :attribute غير صالحة.',
    'numeric' => 'حقل :attribute يجب أن يكون رقمًا.',
    'password' => [
        'letters' => 'حقل :attribute يجب أن يحتوي على حرف واحد على الأقل.',
        'mixed' => 'حقل :attribute يجب أن يحتوي على حرف كبير وحرف صغير على الأقل.',
        'numbers' => 'حقل :attribute يجب أن يحتوي على رقم واحد على الأقل.',
        'symbols' => 'حقل :attribute يجب أن يحتوي على رمز واحد على الأقل.',
        'uncompromised' => 'القيمة المدخلة في :attribute ظهرت في تسريب بيانات. يرجى اختيار قيمة مختلفة.',
    ],
    'present' => 'حقل :attribute يجب أن يكون موجودًا.',
    'present_if' => 'حقل :attribute يجب أن يكون موجودًا عندما يكون :other يساوي :value.',
    'present_unless' => 'حقل :attribute يجب أن يكون موجودًا ما لم يكن :other يساوي :value.',
    'present_with' => 'حقل :attribute يجب أن يكون موجودًا عند وجود :values.',
    'present_with_all' => 'حقل :attribute يجب أن يكون موجودًا عند وجود :values.',
    'prohibited' => 'حقل :attribute غير مسموح به.',
    'prohibited_if' => 'حقل :attribute غير مسموح به عندما يكون :other يساوي :value.',
    'prohibited_if_accepted' => 'حقل :attribute غير مسموح به عند قبول :other.',
    'prohibited_if_declined' => 'حقل :attribute غير مسموح به عند رفض :other.',
    'prohibited_unless' => 'حقل :attribute غير مسموح به إلا إذا كان :other في :values.',
    'prohibits' => 'حقل :attribute يمنع وجود :other.',
    'regex' => 'صيغة حقل :attribute غير صحيحة.',
    'required' => 'حقل :attribute مطلوب.',
    'required_array_keys' => 'حقل :attribute يجب أن يحتوي على مفاتيح: :values.',
    'required_if' => 'حقل :attribute مطلوب عندما يكون :other يساوي :value.',
    'required_if_accepted' => 'حقل :attribute مطلوب عند قبول :other.',
    'required_if_declined' => 'حقل :attribute مطلوب عند رفض :other.',
    'required_unless' => 'حقل :attribute مطلوب إلا إذا كان :other في :values.',
    'required_with' => 'حقل :attribute مطلوب عند وجود :values.',
    'required_with_all' => 'حقل :attribute مطلوب عند وجود :values.',
    'required_without' => 'حقل :attribute مطلوب عند غياب :values.',
    'required_without_all' => 'حقل :attribute مطلوب عند عدم وجود أي من :values.',
    'same' => 'حقل :attribute يجب أن يطابق :other.',
    'size' => [
        'array' => 'حقل :attribute يجب أن يحتوي :size عناصر.',
        'file' => 'حجم الملف في حقل :attribute يجب أن يكون :size كيلوبايت.',
        'numeric' => 'حقل :attribute يجب أن يكون :size.',
        'string' => 'حقل :attribute يجب أن يحتوي :size أحرف.',
    ],
    'starts_with' => 'حقل :attribute يجب أن يبدأ بأحد القيم التالية: :values.',
    'string' => 'حقل :attribute يجب أن يكون نصًا.',
    'timezone' => 'حقل :attribute يجب أن يكون نطاقًا زمنيًا صحيحًا.',
    'unique' => 'القيمة في :attribute مستخدمة بالفعل.',
    'uploaded' => 'فشل رفع :attribute.',
    'uppercase' => 'حقل :attribute يجب أن يكون بأحرف كبيرة.',
    'url' => 'حقل :attribute يجب أن يكون رابطًا صحيحًا.',
    'ulid' => 'حقل :attribute يجب أن يكون ULID صحيح.',
    'uuid' => 'حقل :attribute يجب أن يكون UUID صحيح.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | هنا يمكنك تحديد رسائل تحقق مخصصة لخصائص معينة باستخدام
    | الصيغة "attribute.rule". هذا يتيح تحديد رسالة معينة لقاعدة معينة.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'رسالة مخصصة',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | الأسطر التالية تُستخدم لاستبدال رمز العنصر (attribute) باسم أكثر
    | وضوحًا مثل "البريد الإلكتروني" بدلًا من "email".
    |
    */

    'attributes' => [
        'website_type_id' => 'نوع الموقع',
        'language' => 'اللغة',
        'name' => 'الاسم',
        'subdomain' => 'النطاق الفرعي',
        'about_us' => 'من نحن',
    
        'phone_number' => 'رقم الهاتف',
        'password' => 'كلمة المرور',
        'email' => 'البريد الإلكتروني',
        'address' => 'العنوان',
        'country' => 'البلد',
        'city' => 'المدينة',
    
        'instagram' => 'رابط إنستغرام',
        'facebook' => 'رابط فيسبوك',
        'tiktok' => 'رابط تيك توك',
        'youtube' => 'رابط يوتيوب',
    
        'light_logo' => 'الشعار الفاتح',
        'dark_logo' => 'الشعار الداكن',
        'template_images' => 'صور القالب',
        'template_id' => 'القالب',
        'template_color_id' => 'لون القالب',
        'custom_template_color' => 'لون مخصص للقالب',
        'colors' => 'الألوان',
    
        'acceptSteps' => 'الموافقة على الخطوات',

        'message' => 'الرسالة',
        'subject' => 'الموضوع',
        'type' => 'النوع',

        'code' => 'الكود'
    ],

];
