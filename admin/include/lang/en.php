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
    'INVALID_CREDENTIALS' => 'Invalid credentials provided',
    'REGISTER' => 'Login for Admin',
    'R_LOGIN' => 'Login',

    'INPUT_EMAIL' => 'Email Address',
    'INPUT_PASSWORD' => 'Password',
    'INPUT_NAME' => 'Name',

    'P_DASHBOARD' => 'Dashboard',
    'P_USERS' => 'Users',
    'P_MESSAGES' => 'Messages',
    'P_SETTINGS' => 'Settings',

    'USER' => 'user',
    'ADMIN' => 'admin',
    'M_C_S_R' => 'The user\'s role must be defined.',
    'EDIT_SUCCESS' => 'Data has been effectively modified',
    'DEL_SUCCESS' => 'User deleted successfully',
    'A_Y_D' => 'Are you sure you want to permanently delete this user and their posts?',

    'P_VIEW_PROFILE' => 'View Profile',
    'BTN_EDIT' => 'edit',
    'BTN_DELETE' => 'delete',
    'BTN_CANCEL' => 'cancel',
    'ID' => 'id',
    'FROM' => 'from',
    'TO' => 'to',
    'DATE' => 'date',

    'H_CREATE_POST' => 'Do you want to create a post?',
    'H_NEW_POST' => 'Create New Post',
    'H_POST_CONTENT' => 'What is on your mind?',
    'H_POST_PUBLISH' => 'Publish',

    'PR_JOIN_DATE' => 'Join Date',
    'PR_POST_COUNT' => 'Posts Count',
    'PR_POSTS' => 'Posts',
    'PR_LOGOUT' => 'Logout',
    'PR_CREATE_POST' => 'Create Post',
    'PR_EDIT_PROFILE' => 'Edit Profile',
    'PR_NO_POSTS' => 'No posts to display',

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
    'SE_LANGUAGE' => 'Language'
    ];

    return $lang[$word];

}
?>