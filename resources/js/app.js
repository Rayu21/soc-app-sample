import './bootstrap';
import Profile from "./profile";
import {fadeOutMessages} from "./fademessage"

if (document.querySelector(".profile-nav")){
    new Profile();
}

document.addEventListener("DOMContentLoaded", function() {
    fadeOutMessages();
});
