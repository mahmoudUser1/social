<style>
    body {
        direction: rtl !important;
    }
</style>
<?php
function lang($word){

    static $lang = [
        'S_WELCOME' => 'مرحباً بك في المجتمع',
        'FIRST_TIME' => 'أول مرة تستخدم البرنامج؟',
        'ALREADY_REGISTERED' => 'مسجل من قبل؟',

        'REGISTER_NEW' => 'تسجيل حساب جديد',
        'REGISTER' => 'تسجيل الدخول',
        'R_LOGIN' => 'دخول',
        'R_HOME' => 'الرجوع للصفحة الرئيسية',
        'R_N_LOGIN' => "تسجيل",
        
        'INPUT_EMAIL' => "البريد الإلكتروني",
        'INPUT_PASSWORD' => "كلمة المرور",
        'INPUT_NAME' => "الاسم",
        
        'P_HOME' => "الرئيسية",
        'P_PROFILE' => "الملف الشخصي",
        'P_MESSAGES' => "الرسائل",
        'P_SETTINGS' => "الاعدادات", 

        'H_CREATE_POST' => "هل تريد انشاء post",
        'H_NEW_POST' => "انشاء منشور جديد",
        'H_POST_CONTENT' => "ماذا يدور في ذهنك؟", 
        'H_POST_PUBLISH' => 'نشر', 
        'H_EDIT_POST' => 'تعديل المنشور',

        'PR_JOIN_DATE' => 'تاريخ الانضمام', 
        'PR_POST_COUNT' => 'عدد المنشورات', 
        'PR_POSTS' => 'المنشورات', 
        'PR_LOGOUT' => 'تسجيل الخروج', 
        'PR_CREATE_POST' => 'انشاء منشور', 
        'PR_EDIT_PROFILE' => 'تعديل الملف الشخصي',
        'PR_NO_POSTS' => ' لا توجد منشورات لعرضها',
        'PR_EDIT' => 'تعديل',
        'PR_DELETE' => 'حذف',
        
        'C_USERS' => 'المستخدمون',
        'C_NEW_CONVERSATION' => 'ابدأ محادثة جديدة',
        'C_WELCOME_MESSAGES' => 'مرحباً بك في الرسائل',
        'C_SELECT_CONTACT' => 'اختر صديقاً من القائمة الجانبية لبدء المحادثة',
        'C_TYPE_MESSAGE' => "اكتب رسالتك هنا...",
        'C_ONLINE' => 'متصل الآن',
        'C_NO_MESSAGES' => 'لا توجد رسائل بعد. ابدأ أول رسالة!',
        
        'SE_PERSONAL_DATA' => 'البيانات الشخصية',
        'SE_NO_CHANGE_EMAIL' => 'لا يمكن تغيير البريد الإلكتروني',
        'SE_CHANGE_PASSWORD' => 'تغيير كلمة المرور',
        'SE_CHAT_SETTINGS' =>  'إعدادات الدردشة',
        'SE_APP_SETTINGS' =>  'إعدادات التطبيق',
        'SE_ABOUT' =>  'حول التطبيق',
        'SE_SAVE_CHANGES' =>  'حفظ التغييرات',
        'SE_CURRENT_PASSWORD' =>  'كلمة المرور الحالية',
        'SE_NEW_PASSWORD' =>  'كلمة المرور الجديدة',
        'SE_CONFIRM_PASSWORD' =>  'تأكيد كلمة المرور الجديدة',
        'SE_PASSWORD_LENGTH' =>  'يجب أن تكون 6 أحرف على الأقل',
        'SE_UPDATE_PASSWORD' =>  'تحديث كلمة المرور',
        'SE_APP_NAME' =>  'المجتمع - الشبكة الاجتماعية',
        'SE_VERSION' =>  'نسخة',
        'SE_SELECT_THEME' =>  'اختيار الثيم',
        'SE_FEATURES' =>  'المميزات',
        'SE_FRIENDS_SYSTEM' =>  'نظام الأصدقاء',
        'SE_PRIVATE_MESSAGES' =>  'الرسائل الخاصة',
        'SE_NOTIFICATIONS' =>  ' الإشعارات',
        'SE_THANK_YOU' =>  'شكراً لاستخدامك تطبيقنا. نتمنى لك تجربة ممتعة!',
        'SE_UPDATE' =>  'تحديث',
        'SE_ENTER_NAME' =>  'الرجاء إدخال الاسم',
        'SE_PROFILE_UPDATED' =>  'تم تحديث البيانات الشخصية بنجاح',
        'SE_FILL_ALL_FIELDS' =>  'الرجاء ملء جميع الحقول',
        'SE_PASSWORDS_DO_NOT_MATCH' =>  'كلمات المرور الجديدة غير متطابقة',
        'SE_CURRENT_PASSWORD_INCORRECT' =>  'كلمة المرور الحالية غير صحيحة',
        'SE_PASSWORD_CHANGED_SUCCESSFULLY' =>  'تم تغيير كلمة المرور بنجاح',
        'SE_CHAT_BACKGROUND_UPDATED' =>  'تم تحديث خلفية الدردشة بنجاح',
        'SE_APP_LANGUAGE_UPDATED' =>  'تم تحديث لغة التطبيق بنجاح',
        'SE_LANGUAGE' =>  'اللغة'
    ];

    return $lang[$word];

}