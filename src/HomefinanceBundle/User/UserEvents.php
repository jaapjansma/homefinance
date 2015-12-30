<?php

namespace HomefinanceBundle\User;

class UserEvents {

    const EMAIL_REGISTRATION_SUCCESS = 'homefinance.user.email_registration.success';
    const REGISTRATION_SUCCESS = 'homefinance.user.registration.success';
    const CONFIRMATION_SUCCESS = 'homefinance.user.confirmation.success';
    const NEW_EMAIL_ADDRESS = 'homefinance.user.new_email_address';
    const RESET_NEW_EMAIL_ADDRESS = 'homefinance.user.reset_new_email_address';
    const LOST_PASSWORD = 'homefinance.user.lost_password';
    const RESET_PASSWORD_SUCCESS = 'homefinance.user.reset_password.success';
    const PASSWORD_CHANGED = 'homefinance.user.password_changed';
    const PROFILE_UPDATED = 'homefinance.user.profile_updated';

}