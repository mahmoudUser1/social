<style>
    body {
        direction: ltr !important;
    }

    @media (min-width: 768px) {
        nav {
            left: 0 !important;
            bottom: 0;
        }

        .menuToggle {
            display: none;
        }
    }
</style>
<?php
function lang($word)
{

    static $lang = [
    'S_WELCOME' => 'Welcome to the Community',
    'FIRST_TIME' => 'First time using the app?',
    'ALREADY_REGISTERED' => 'Already registered?',

    'REGISTER_NEW' => 'Create New Account',
    'REGISTER' => 'Login',
    'R_LOGIN' => 'Login',
    'R_HOME' => 'Back to Home',
    'R_N_LOGIN' => 'Register',

    'INPUT_EMAIL' => 'Email Address',
    'INPUT_PASSWORD' => 'Password',
    'INPUT_NAME' => 'Name',

    'P_HOME' => 'Home',
    'P_PROFILE' => 'Profile',
    'P_MESSAGES' => 'Messages',
    'P_SETTINGS' => 'Settings',

    'H_CREATE_POST' => 'Do you want to create a post?',
    'H_NEW_POST' => 'Create New Post',
    'H_POST_CONTENT' => 'What is on your mind?',
    'H_POST_PUBLISH' => 'Publish',
    'H_EDIT_POST' => 'Edit Post',

    'PR_JOIN_DATE' => 'Join Date',
    'PR_POST_COUNT' => 'Posts Count',
    'PR_POSTS' => 'Posts',
    'PR_LOGOUT' => 'Logout',
    'PR_CREATE_POST' => 'Create Post',
    'PR_EDIT_PROFILE' => 'Edit Profile',
    'PR_NO_POSTS' => 'No posts to display',
    'PR_EDIT' => 'Edit',
    'PR_DELETE' => 'Delete',
    'BTN_CANCEL' => 'Cancel',

    'C_USERS' => 'Users',
    'C_NEW_CONVERSATION' => 'Start New Conversation',
    'C_WELCOME_MESSAGES' => 'Welcome to Messages',
    'C_SELECT_CONTACT' => 'Select a friend from the sidebar to start chatting',
    'C_TYPE_MESSAGE' => 'Type your message here...',
    'C_ONLINE' => 'Online Now',
    'C_NO_MESSAGES' => 'No messages yet. Start the first conversation!',

    'SE_PERSONAL_DATA' => 'Personal Information',
    'SE_NO_CHANGE_EMAIL' => 'Email address cannot be changed',
    'SE_CHANGE_PASSWORD' => 'Change Password',
    'SE_CHAT_SETTINGS' => 'Chat Settings',
    'SE_APP_SETTINGS' => 'Application Settings',
    'SE_ABOUT' => 'About Application',
    'SE_SAVE_CHANGES' => 'Save Changes',
    'SE_CURRENT_PASSWORD' => 'Current Password',
    'SE_NEW_PASSWORD' => 'New Password',
    'SE_CONFIRM_PASSWORD' => 'Confirm New Password',
    'SE_PASSWORD_LENGTH' => 'Password must be at least 6 characters',
    'SE_UPDATE_PASSWORD' => 'Update Password',
    'SE_APP_NAME' => 'Community - Social Network',
    'SE_VERSION' => 'Version',
    'SE_SELECT_THEME' => 'Select Theme',
    'SE_FEATURES' => 'Features',
    'SE_FRIENDS_SYSTEM' => 'Friends System',
    'SE_PRIVATE_MESSAGES' => 'Private Messages',
    'SE_NOTIFICATIONS' => 'Notifications',
    'SE_THANK_YOU' => 'Thank you for using our application. We hope you enjoy your experience!',
    'SE_UPDATE' => 'Update',
    'SE_ENTER_NAME' => 'Please enter your name',
    'SE_PROFILE_UPDATED' => 'Personal information updated successfully',
    'SE_FILL_ALL_FIELDS' => 'Please fill in all fields',
    'SE_PASSWORDS_DO_NOT_MATCH' => 'New passwords do not match',
    'SE_CURRENT_PASSWORD_INCORRECT' => 'Current password is incorrect',
    'SE_PASSWORD_CHANGED_SUCCESSFULLY' => 'Password changed successfully',
    'SE_CHAT_BACKGROUND_UPDATED' => 'Chat background updated successfully',
    'SE_APP_LANGUAGE_UPDATED' => 'Application language updated successfully',
    'SE_LANGUAGE' => 'Language',

    // Services / Dashboard labels
    'SV_SERVICES' => 'Services',
    'SV_MESSAGES' => 'Messages',
    'SV_FRIENDS' => 'Friends',
    'SV_POSTS' => 'Posts',
    'SV_NOTIFICATIONS' => 'Notifications',
    'SV_PREMIUM_FEATURES' => 'Premium Features',
    'SV_UNLIMITED_MESSAGES' => 'Unlimited messages',
    'SV_CREATE_GROUPS' => 'Create groups',
    'SV_FILE_SHARING' => 'File sharing',
    'SV_PRIORITY_SUPPORT' => 'Priority support',

    'D_TOTAL_USERS' => 'Total Users',
    'D_ACTIVE_USERS' => 'Active Users',
    'D_TOTAL_ADMINS' => 'Total Admins',
    'D_SYSTEM_MANAGERS' => 'System Managers',
    'D_TOTAL_POSTS' => 'Total Posts',
    'D_USER_POSTS' => 'User Posts'
    ,
    // Additional dashboard / misc
    'D_SUMMARY' => 'Last data summary for users, admins, and posts.',
    'POST_EMPTY' => 'Post content cannot be empty.',
    'POST_PUBLISHED' => 'Post published successfully.',
    'D_LAST_USERS' => 'Last 10 Users',
    'D_ADMINS' => 'Admins',
    'D_LAST_POSTS' => 'Last 5 Posts',
    'NO_USERS_FOUND' => 'No users found.',
    'NO_ADMINS_FOUND' => 'No admins found.',
    'NO_POSTS_FOUND' => 'No posts found.',
    'UNKNOWN' => 'Unknown'
    ];

    return $lang[$word];

}
?>