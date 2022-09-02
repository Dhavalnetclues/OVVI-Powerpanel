/*!
 *  Lang.js for Laravel localization in JavaScript.
 *
 *  @version 1.1.5
 *  @license MIT https://github.com/rmariuzzo/Lang.js/blob/master/LICENSE
 *  @site    https://github.com/rmariuzzo/Lang.js
 *  @author  Rubens Mariuzzo <rubens@mariuzzo.com>
 */

$(document).on('focusout', 'input', function() 
{
    var arr=['password','file','select'];
    if(!jQuery.inArray( $(this).attr('type'), arr )){
        var ip=$.trim($(this).val());
        $(this).val('');
        $(this).val( ip );
    }
});


(function(root, factory) {
    "use strict";
    if (typeof define === "function" && define.amd) {
        define([], factory)
    } else if (typeof exports === "object") {
        module.exports = factory()
    } else {
        root.Lang = factory()
    }
})(this, function() {
    "use strict";

    function inferLocale() {
        if (typeof document !== "undefined" && document.documentElement) {
            return document.documentElement.lang
        }
    }

    function convertNumber(str) {
        if (str === "-Inf") {
            return -Infinity
        } else if (str === "+Inf" || str === "Inf") {
            return Infinity
        }
        return parseInt(str, 10)
    }
    var intervalRegexp = /^({\s*(\-?\d+(\.\d+)?[\s*,\s*\-?\d+(\.\d+)?]*)\s*})|([\[\]])\s*(-Inf|\-?\d+(\.\d+)?)\s*,\s*(\+?Inf|\-?\d+(\.\d+)?)\s*([\[\]])$/;
    var anyIntervalRegexp = /({\s*(\-?\d+(\.\d+)?[\s*,\s*\-?\d+(\.\d+)?]*)\s*})|([\[\]])\s*(-Inf|\-?\d+(\.\d+)?)\s*,\s*(\+?Inf|\-?\d+(\.\d+)?)\s*([\[\]])/;
    var defaults = {
        locale: "en"
    };
    var Lang = function(options) {
        options = options || {};
        this.locale = options.locale || inferLocale() || defaults.locale;
        this.fallback = options.fallback;
        this.messages = options.messages
    };
    Lang.prototype.setMessages = function(messages) {
        this.messages = messages
    };
    Lang.prototype.getLocale = function() {
        return this.locale || this.fallback
    };
    Lang.prototype.setLocale = function(locale) {
        this.locale = locale
    };
    Lang.prototype.getFallback = function() {
        return this.fallback
    };
    Lang.prototype.setFallback = function(fallback) {
        this.fallback = fallback
    };
    Lang.prototype.has = function(key, locale) {
        if (typeof key !== "string" || !this.messages) {
            return false
        }
        return this._getMessage(key, locale) !== null
    };
    Lang.prototype.get = function(key, replacements, locale) {
        if (!this.has(key)) {
            return key
        }
        var message = this._getMessage(key, locale);
        if (message === null) {
            return key
        }
        if (replacements) {
            message = this._applyReplacements(message, replacements)
        }
        return message
    };
    Lang.prototype.trans = function(key, replacements) {
        return this.get(key, replacements)
    };
    Lang.prototype.choice = function(key, number, replacements, locale) {
        replacements = typeof replacements !== "undefined" ? replacements : {};
        replacements.count = number;
        var message = this.get(key, replacements, locale);
        if (message === null || message === undefined) {
            return message
        }
        var messageParts = message.split("|");
        var explicitRules = [];
        for (var i = 0; i < messageParts.length; i++) {
            messageParts[i] = messageParts[i].trim();
            if (anyIntervalRegexp.test(messageParts[i])) {
                var messageSpaceSplit = messageParts[i].split(/\s/);
                explicitRules.push(messageSpaceSplit.shift());
                messageParts[i] = messageSpaceSplit.join(" ")
            }
        }
        if (messageParts.length === 1) {
            return message
        }
        for (var j = 0; j < explicitRules.length; j++) {
            if (this._testInterval(number, explicitRules[j])) {
                return messageParts[j]
            }
        }
        var pluralForm = this._getPluralForm(number);
        return messageParts[pluralForm]
    };
    Lang.prototype.transChoice = function(key, count, replacements) {
        return this.choice(key, count, replacements)
    };
    Lang.prototype._parseKey = function(key, locale) {
        if (typeof key !== "string" || typeof locale !== "string") {
            return null
        }
        var segments = key.split(".");
        var source = segments[0].replace(/\//g, ".");
        return {
            source: locale + "." + source,
            sourceFallback: this.getFallback() + "." + source,
            entries: segments.slice(1)
        }
    };
    Lang.prototype._getMessage = function(key, locale) {
        locale = locale || this.getLocale();
        key = this._parseKey(key, locale);
        
        if(key) {
            if (this.messages[key.source] === undefined && this.messages[key.sourceFallback] === undefined) {
                return null
            }
            var message = this.messages[key.source];
            var entries = key.entries.slice();
            while (entries.length && (message = message[entries.shift()]));
            if (typeof message !== "string" && this.messages[key.sourceFallback]) {
                message = this.messages[key.sourceFallback];
                entries = key.entries.slice();
                while (entries.length && (message = message[entries.shift()]));
            }
        }
        if (typeof message !== "string") {
            return null
        }
        return message
    };
    Lang.prototype._applyReplacements = function(message, replacements) {
        for (var replace in replacements) {
            message = message.split(":" + replace).join(replacements[replace])
        }
        return message
    };
    Lang.prototype._testInterval = function(count, interval) {
        if (typeof interval !== "string") {
            throw "Invalid interval: should be a string."
        }
        interval = interval.trim();
        var matches = interval.match(intervalRegexp);
        if (!matches) {
            throw new "Invalid interval: " + interval
        }
        if (matches[2]) {
            var items = matches[2].split(",");
            for (var i = 0; i < items.length; i++) {
                if (parseInt(items[i], 10) === count) {
                    return true
                }
            }
        } else {
            matches = matches.filter(function(match) {
                return !!match
            });
            var leftDelimiter = matches[1];
            var leftNumber = convertNumber(matches[2]);
            var rightNumber = convertNumber(matches[3]);
            var rightDelimiter = matches[4];
            return (leftDelimiter === "[" ? count >= leftNumber : count > leftNumber) && (rightDelimiter === "]" ? count <= rightNumber : count < rightNumber)
        }
        return false
    };
    Lang.prototype._getPluralForm = function(count) {
        switch (this.locale) {
            case "az":
            case "bo":
            case "dz":
            case "id":
            case "ja":
            case "jv":
            case "ka":
            case "km":
            case "kn":
            case "ko":
            case "ms":
            case "th":
            case "tr":
            case "vi":
            case "zh":
                return 0;
            case "af":
            case "bn":
            case "bg":
            case "ca":
            case "da":
            case "de":
            case "el":
            case "en":
            case "eo":
            case "es":
            case "et":
            case "eu":
            case "fa":
            case "fi":
            case "fo":
            case "fur":
            case "fy":
            case "gl":
            case "gu":
            case "ha":
            case "he":
            case "hu":
            case "is":
            case "it":
            case "ku":
            case "lb":
            case "ml":
            case "mn":
            case "mr":
            case "nah":
            case "nb":
            case "ne":
            case "nl":
            case "nn":
            case "no":
            case "om":
            case "or":
            case "pa":
            case "pap":
            case "ps":
            case "pt":
            case "so":
            case "sq":
            case "sv":
            case "sw":
            case "ta":
            case "te":
            case "tk":
            case "ur":
            case "zu":
                return count == 1 ? 0 : 1;
            case "am":
            case "bh":
            case "fil":
            case "fr":
            case "gun":
            case "hi":
            case "hy":
            case "ln":
            case "mg":
            case "nso":
            case "xbr":
            case "ti":
            case "wa":
                return count === 0 || count === 1 ? 0 : 1;
            case "be":
            case "bs":
            case "hr":
            case "ru":
            case "sr":
            case "uk":
                return count % 10 == 1 && count % 100 != 11 ? 0 : count % 10 >= 2 && count % 10 <= 4 && (count % 100 < 10 || count % 100 >= 20) ? 1 : 2;
            case "cs":
            case "sk":
                return count == 1 ? 0 : count >= 2 && count <= 4 ? 1 : 2;
            case "ga":
                return count == 1 ? 0 : count == 2 ? 1 : 2;
            case "lt":
                return count % 10 == 1 && count % 100 != 11 ? 0 : count % 10 >= 2 && (count % 100 < 10 || count % 100 >= 20) ? 1 : 2;
            case "sl":
                return count % 100 == 1 ? 0 : count % 100 == 2 ? 1 : count % 100 == 3 || count % 100 == 4 ? 2 : 3;
            case "mk":
                return count % 10 == 1 ? 0 : 1;
            case "mt":
                return count == 1 ? 0 : count === 0 || count % 100 > 1 && count % 100 < 11 ? 1 : count % 100 > 10 && count % 100 < 20 ? 2 : 3;
            case "lv":
                return count === 0 ? 0 : count % 10 == 1 && count % 100 != 11 ? 1 : 2;
            case "pl":
                return count == 1 ? 0 : count % 10 >= 2 && count % 10 <= 4 && (count % 100 < 12 || count % 100 > 14) ? 1 : 2;
            case "cy":
                return count == 1 ? 0 : count == 2 ? 1 : count == 8 || count == 11 ? 2 : 3;
            case "ro":
                return count == 1 ? 0 : count === 0 || count % 100 > 0 && count % 100 < 20 ? 1 : 2;
            case "ar":
                return count === 0 ? 0 : count == 1 ? 1 : count == 2 ? 2 : count % 100 >= 3 && count % 100 <= 10 ? 3 : count % 100 >= 11 && count % 100 <= 99 ? 4 : 5;
            default:
                return 0
        }
    };
    return Lang
});
(function() {
    //Get dynamically language if multilingual
    Lang = new Lang();
    Lang.setLocale('en');
    Lang.setFallback('en');
    Lang.setMessages({
        "ar.auth": {
            "failed": "\u0628\u064a\u0627\u0646\u0627\u062a \u0627\u0644\u0627\u0639\u062a\u0645\u0627\u062f \u0647\u0630\u0647 \u063a\u064a\u0631 \u0645\u062a\u0637\u0627\u0628\u0642\u0629 \u0645\u0639 \u0627\u0644\u0628\u064a\u0627\u0646\u0627\u062a \u0627\u0644\u0645\u0633\u062c\u0644\u0629 \u0644\u062f\u064a\u0646\u0627.",
            "throttle": "\u0639\u062f\u062f \u0643\u0628\u064a\u0631 \u062c\u062f\u0627 \u0645\u0646 \u0645\u062d\u0627\u0648\u0644\u0627\u062a \u0627\u0644\u062f\u062e\u0648\u0644. \u064a\u0631\u062c\u0649 \u0627\u0644\u0645\u062d\u0627\u0648\u0644\u0629 \u0645\u0631\u0629 \u0623\u062e\u0631\u0649 \u0628\u0639\u062f :seconds \u062b\u0627\u0646\u064a\u0629."
        },
        "ar.pagination": {
            "previous": "&laquo; \u0627\u0644\u0633\u0627\u0628\u0642",
            "next": "\u0627\u0644\u062a\u0627\u0644\u064a &raquo;"
        },
        "ar.passwords": {
            "password": "\u064a\u062c\u0628 \u0623\u0646 \u0644\u0627 \u064a\u0642\u0644 \u0637\u0648\u0644 \u0643\u0644\u0645\u0629 \u0627\u0644\u0633\u0631 \u0639\u0646 \u0633\u062a\u0629 \u0623\u062d\u0631\u0641\u060c \u0643\u0645\u0627 \u064a\u062c\u0628 \u0623\u0646 \u062a\u062a\u0637\u0627\u0628\u0642 \u0645\u0639 \u062d\u0642\u0644 \u0627\u0644\u062a\u0623\u0643\u064a\u062f",
            "reset": "\u062a\u0645\u062a \u0625\u0639\u0627\u062f\u0629 \u062a\u0639\u064a\u064a\u0646 \u0643\u0644\u0645\u0629 \u0627\u0644\u0633\u0631",
            "sent": "\u062a\u0645 \u0625\u0631\u0633\u0627\u0644 \u062a\u0641\u0627\u0635\u064a\u0644 \u0627\u0633\u062a\u0639\u0627\u062f\u0629 \u0643\u0644\u0645\u0629 \u0627\u0644\u0633\u0631 \u0627\u0644\u062e\u0627\u0635\u0629 \u0628\u0643 \u0625\u0644\u0649 \u0628\u0631\u064a\u062f\u0643 \u0627\u0644\u0625\u0644\u0643\u062a\u0631\u0648\u0646\u064a",
            "token": ".\u0631\u0645\u0632 \u0627\u0633\u062a\u0639\u0627\u062f\u0629 \u0643\u0644\u0645\u0629 \u0627\u0644\u0633\u0631 \u0627\u0644\u0630\u064a \u0623\u062f\u062e\u0644\u062a\u0647 \u063a\u064a\u0631 \u0635\u062d\u064a\u062d",
            "user": "\u0644\u0645 \u064a\u062a\u0645 \u0627\u0644\u0639\u062b\u0648\u0631 \u0639\u0644\u0649 \u0623\u064a\u0651 \u062d\u0633\u0627\u0628\u064d \u0628\u0647\u0630\u0627 \u0627\u0644\u0639\u0646\u0648\u0627\u0646 \u0627\u0644\u0625\u0644\u0643\u062a\u0631\u0648\u0646\u064a"
        },
        "ar.template": {
            "selectcategory": "Select Parent Category",
            "myprofile": "My Profile",
            "mycalendar": "My Calendar",
            "changepassword": "Change Password",
            "logout": "Log Out",
            "viewsite": "View Site",
            "globalsearch": "Search for something",
            "dashboard": "Dashboard",
            "banner": "Banner",
            "pages": "Pages",
            "services": "Services",
            "photoalbum": "Photo Album",
            "staticblock": "Static Blocks",
            "blog": "Blog",
            "managenews": "Manage News",
            "news": "News",
            "newscategory": "News Category",
            "menu": "Menu",
            "testimonial": "Testimonial",
            "popupcontent": "Popup Content",
            "leads": "Leads",
            "changeprofile": "Change Profile",
            "home": "Home",
            "managebanner": "Manage Banners",
            "banners": "Banners",
            "managepages": "Manage Pages",
            "addpage": "Add Page",
            "editpage": "Edit Page",
            "manageservices": "Manage Services",
            "Services": "services",
            "addservice": "Add Service",
            "editservice": "Edit Service",
            "managephotoalbums": "Manage Photo Albums",
            "photoalbums": "Photo Albums",
            "editphotoalbum": "Edit Photo Album",
            "name": "Name",
            "email": "Email",
            "timezone": "Time Zone",
            "profilephoto": "Profile Photo",
            "imagerecommendation": "Recommended image size is Height 1080px * Width 1920px, only *.jpg, *.jpeg, *.png, *.gif image formats are supported. Image should be maximum size of 5 MB",
            "updateprofile": "Update Profile",
            "oldpassword": "Old Password",
            "newpassword": "New Password",
            "confirmpassword": "Confirm Password",
            "updatepassword": "Update Password",
            "webhits": "Web Hits",
            "mobilehits": "Mobile Hits",
            "viewmore": "View More",
            "title": "Title",
            "details": "Details",
            "createdatetime": "Created  Date \/ Time",
            "cmspages": "CMS Pages",
            "seeallrecords": "See All Records",
            "norecordsavailable": "No records available",
            "contactuslead": "Contact Us Lead",
            "emailid": "Email ID",
            "faq": "FAQs",
            "newsletterleads": "Newsletter Leads",
            "contact": "Contact",
            "emaillog": "Email Log",
            "rolemanager": "Role Manager",
            "usermanagement": "User Management",
            "team": "Team",
            "recentupdates": "Recent Updates",
            "settings": "Settings",
            "generalsettings": "General Settings",
            "trash": "Trash",
            "logmanager": "Log Manager",
            "filterby": "Filter By",
            "publish": "Publish",
            "unpublish": "Unpublish",
            "selectstatus": "Select Status",
            "selectpage": "Select Page",
            "addbanner": "Add Banner",
            "search": "Search",
            "id": "ID",
            "page": "Page",
            "bannertype": "Banner Type",
            "image": "Image",
            "displayorder": "Display Order",
            "actions": "Actions",
            "back": "Back",
            "homebanner": "Home Banner",
            "innerbanner": "Inner Banner",
            "selectbanner": "Select Banner",
            "homebannerrecomand": "Recommended image size is Height 1900px * Width 900px, only *.jpg, *.jpeg, *.png, *.gif image formats are supported. Image should be maximum size of 5 MB",
            "innerbannerrecomand": "Recommended image size is Height 1900px * Width 500px, only *.jpg, *.jpeg, *.png, *.gif image formats are supported. Image should be maximum size of 5 MB",
            "description": "Description",
            "displayinformation": "Display Information",
            "defaultbanner": "Default Banner",
            "saveandedit": "Save &amp; Keep Editing",
            "saveandexit": "Save &amp; Exit",
            "cancel": "Cancel",
            "editbanner": "Edit Banner",
            "edit": "Edit",
            "display": "Display",
            "yes": "Yes",
            "no": "No",
            "delete": "Delete",
            "confirm": "Confirm",
            "close": "Close",
            "seoinformation": "SEO Information",
            "autogenerate": "Auto Generate",
            "metatitle": "Meta Title",
            "metakeyword": "Meta Keyword",
            "metadescription": "Meta Description",
            "addnewpage": "Add New Page",
            "module": "Module",
            "selectmodule": "Select Module",
            "url": "Url",
            "contents": "Contents",
            "addnewservice": "Add New service",
            "order": "Order",
            "selectimage": "Select Image",
            "serviceimagerecomand": "Recommended image size is Height 700px * Width 500px, only *.jpg, *.jpeg, *.png, *.gif image formats are supported. Image should be maximum size of 5 MB",
            "category": "Category",
            "addphotoalbum": "Add Photo Album",
            "totalimages": "Total Images",
            "gallery": "Gallery",
            "selectphoto": "Select Photo",
            "photoalbumimagerecomand": "Recommended image size is Height 400px * Width 300px, only *.jpg, *.jpeg, *.png, *.gif image formats are supported. Image should be maximum size of 5 MB",
            "addnewphoto": "Add New Photo",
            "addphoto": "Add Photo",
            "save": "Save",
            "uploadyourimages": "Upload your images",
            "photogallery": "Photo Gallery",
            "caption": "Caption",
            "adv_name": "Advertise Name",
            'shortdescription': 'Short Description',
            'phone': 'Phone',
            'designation': 'Designation',
            'video': "video",
            'oneRecordDeleted': 'Please select at-least one record to delete',
            'selectedDeleted': 'Are you sure you want to permanently delete this data? You will not able to retrieve it. Press DELETE to confirm.',
            'author': 'Author',
            'category': 'Category',
            'pages': 'pages',
            'positions': 'Positions',
            'date': 'Date',
            'link': 'Link',
            'startDateTime': 'Start date and time',
            'endDateTime': 'End date and time',
            'question': 'Question',
            'answer': 'Answer',
            'metaTag': 'Meta tag',
            'bannerModule': {
                'bannerValidation': 'Banner',
            },
            'managePopup': {
                'startDateTime': 'Start date and time',
                'endDateTime': 'End date and time'
            },
            'contactModule': {
                'address': 'Address',
                'primary': 'Primary',
                'maxlength': 'You reach the maximum limit.',

            },
            'settingModule': {
                'siteName': 'Site name',
                'frontLogo': 'Front Logo',
                'mailerIsRequired': 'Mailer',
                'smtp': 'Host',
                'smtpUserName': 'Smtp username',
                'smtpPassword': 'Smtp password',
                'smtpPort': 'Smtp port',
                'smtpSenderName': 'Smtp sender name',
                'smtpSenderId': 'Smtp sender id',
                'mailContent': 'Email Signature',
                'defaultCurrencySymbol': 'Default currency symbol',
                'googleAnalyticCode': 'Google analytic code',
                'googleTagManger': 'Google tag manager for body',
                'validUrl': 'url',
                'facebookId': 'Facebook id',
                'facebookApi': 'Facebook api',
                'facebookSecretKey': 'Facebook secret key',
                'facebookAccessToken': 'Facebook access token',
                'twitterApi': 'Twitter api',
                'twitterSecretKey': 'Twitter secret key',
                'twitterAccessToken': 'Twitter access token',
                'twitterAccessTokenKey': 'Twitter access token key',
                'linkedinApi': 'Linkedin api',
                'linkedinSecretKey': 'Linkedin secret key',
                'linkedinAccessToken': 'Linkedin access token',
                'linkedinAccessTokenKey': 'Linkedin access token key',
                'googleMapKey': 'Google map key',
                'googleCaptchaKey': 'Google captcha key',
                'resetOption': 'Reset option',
            },
            'userModule': {
                'userName': 'Name',
                'userEmail': 'Email',
                'userPassword': 'Password',
                'confirmPassord': 'Confirm password',
                'userRole': 'Role',
            },
            'sponserModule': {
                'sponserName': 'Name',
            },
        },
        "ar.validation": {
            "accepted": "\u064a\u062c\u0628 \u0642\u0628\u0648\u0644 \u0627\u0644\u062d\u0642\u0644 :attribute",
            "active_url": "\u0627\u0644\u062d\u0642\u0644 :attribute \u0644\u0627 \u064a\u064f\u0645\u062b\u0651\u0644 \u0631\u0627\u0628\u0637\u064b\u0627 \u0635\u062d\u064a\u062d\u064b\u0627",
            "after": "\u064a\u062c\u0628 \u0639\u0644\u0649 \u0627\u0644\u062d\u0642\u0644 :attribute \u0623\u0646 \u064a\u0643\u0648\u0646 \u062a\u0627\u0631\u064a\u062e\u064b\u0627 \u0644\u0627\u062d\u0642\u064b\u0627 \u0644\u0644\u062a\u0627\u0631\u064a\u062e :date.",
            "after_or_equal": "The :attribute must be a date after or equal to :date.",
            "alpha": "\u064a\u062c\u0628 \u0623\u0646 \u0644\u0627 \u064a\u062d\u062a\u0648\u064a \u0627\u0644\u062d\u0642\u0644 :attribute \u0633\u0648\u0649 \u0639\u0644\u0649 \u062d\u0631\u0648\u0641",
            "alpha_dash": "\u064a\u062c\u0628 \u0623\u0646 \u0644\u0627 \u064a\u062d\u062a\u0648\u064a \u0627\u0644\u062d\u0642\u0644 :attribute \u0639\u0644\u0649 \u062d\u0631\u0648\u0641\u060c \u0623\u0631\u0642\u0627\u0645 \u0648\u0645\u0637\u0651\u0627\u062a.",
            "alpha_num": "\u064a\u062c\u0628 \u0623\u0646 \u064a\u062d\u062a\u0648\u064a :attribute \u0639\u0644\u0649 \u062d\u0631\u0648\u0641\u064d \u0648\u0623\u0631\u0642\u0627\u0645\u064d \u0641\u0642\u0637",
            "array": "\u064a\u062c\u0628 \u0623\u0646 \u064a\u0643\u0648\u0646 \u0627\u0644\u062d\u0642\u0644 :attribute \u064b\u0645\u0635\u0641\u0648\u0641\u0629",
            "before": "\u064a\u062c\u0628 \u0639\u0644\u0649 \u0627\u0644\u062d\u0642\u0644 :attribute \u0623\u0646 \u064a\u0643\u0648\u0646 \u062a\u0627\u0631\u064a\u062e\u064b\u0627 \u0633\u0627\u0628\u0642\u064b\u0627 \u0644\u0644\u062a\u0627\u0631\u064a\u062e :date.",
            "before_or_equal": "The :attribute must be a date before or equal to :date.",
            "between": {
                "numeric": "\u064a\u062c\u0628 \u0623\u0646 \u062a\u0643\u0648\u0646 \u0642\u064a\u0645\u0629 :attribute \u0645\u062d\u0635\u0648\u0631\u0629 \u0645\u0627 \u0628\u064a\u0646 :min \u0648 :max.",
                "file": "\u064a\u062c\u0628 \u0623\u0646 \u064a\u0643\u0648\u0646 \u062d\u062c\u0645 \u0627\u0644\u0645\u0644\u0641 :attribute \u0645\u062d\u0635\u0648\u0631\u064b\u0627 \u0645\u0627 \u0628\u064a\u0646 :min \u0648 :max \u0643\u064a\u0644\u0648\u0628\u0627\u064a\u062a.",
                "string": "\u064a\u062c\u0628 \u0623\u0646 \u064a\u0643\u0648\u0646 \u0639\u062f\u062f \u062d\u0631\u0648\u0641 \u0627\u0644\u0646\u0651\u0635 :attribute \u0645\u062d\u0635\u0648\u0631\u064b\u0627 \u0645\u0627 \u0628\u064a\u0646 :min \u0648 :max",
                "array": "\u064a\u062c\u0628 \u0623\u0646 \u064a\u062d\u062a\u0648\u064a :attribute \u0639\u0644\u0649 \u0639\u062f\u062f \u0645\u0646 \u0627\u0644\u0639\u0646\u0627\u0635\u0631 \u0645\u062d\u0635\u0648\u0631\u064b\u0627 \u0645\u0627 \u0628\u064a\u0646 :min \u0648 :max"
            },
            "boolean": "\u064a\u062c\u0628 \u0623\u0646 \u062a\u0643\u0648\u0646 \u0642\u064a\u0645\u0629 \u0627\u0644\u062d\u0642\u0644 :attribute \u0625\u0645\u0627 true \u0623\u0648 false ",
            "confirmed": "\u062d\u0642\u0644 \u0627\u0644\u062a\u0623\u0643\u064a\u062f \u063a\u064a\u0631 \u0645\u064f\u0637\u0627\u0628\u0642 \u0644\u0644\u062d\u0642\u0644 :attribute",
            "date": "\u0627\u0644\u062d\u0642\u0644 :attribute \u0644\u064a\u0633 \u062a\u0627\u0631\u064a\u062e\u064b\u0627 \u0635\u062d\u064a\u062d\u064b\u0627",
            "date_format": "\u0644\u0627 \u064a\u062a\u0648\u0627\u0641\u0642 \u0627\u0644\u062d\u0642\u0644 :attribute \u0645\u0639 \u0627\u0644\u0634\u0643\u0644 :format.",
            "different": "\u064a\u062c\u0628 \u0623\u0646 \u064a\u0643\u0648\u0646 \u0627\u0644\u062d\u0642\u0644\u0627\u0646 :attribute \u0648 :other \u0645\u064f\u062e\u062a\u0644\u0641\u0627\u0646",
            "digits": "\u064a\u062c\u0628 \u0623\u0646 \u064a\u062d\u062a\u0648\u064a \u0627\u0644\u062d\u0642\u0644 :attribute \u0639\u0644\u0649 :digits \u0631\u0642\u0645\u064b\u0627\/\u0623\u0631\u0642\u0627\u0645",
            "digits_between": "\u064a\u062c\u0628 \u0623\u0646 \u064a\u062d\u062a\u0648\u064a \u0627\u0644\u062d\u0642\u0644 :attribute \u0645\u0627 \u0628\u064a\u0646 :min \u0648 :max \u0631\u0642\u0645\u064b\u0627\/\u0623\u0631\u0642\u0627\u0645 ",
            "dimensions": "\u0627\u0644\u0640 :attribute \u064a\u062d\u062a\u0648\u064a \u0639\u0644\u0649 \u0623\u0628\u0639\u0627\u062f \u0635\u0648\u0631\u0629 \u063a\u064a\u0631 \u0635\u0627\u0644\u062d\u0629.",
            "distinct": "\u0644\u0644\u062d\u0642\u0644 :attribute \u0642\u064a\u0645\u0629 \u0645\u064f\u0643\u0631\u0651\u0631\u0629.",
            "email": "\u064a\u062c\u0628 \u0623\u0646 \u064a\u0643\u0648\u0646 :attribute \u0639\u0646\u0648\u0627\u0646 \u0628\u0631\u064a\u062f \u0625\u0644\u0643\u062a\u0631\u0648\u0646\u064a \u0635\u062d\u064a\u062d \u0627\u0644\u0628\u064f\u0646\u064a\u0629",
            "exists": "\u0627\u0644\u062d\u0642\u0644 :attribute \u0644\u0627\u063a\u064d",
            "file": "\u0627\u0644\u0640 :attribute \u064a\u062c\u0628 \u0623\u0646 \u064a\u0643\u0648\u0646 \u0645\u0646 \u0646\u0648\u0639 \u0645\u0644\u0641.",
            "filled": "\u0627\u0644\u062d\u0642\u0644 :attribute \u0625\u062c\u0628\u0627\u0631\u064a",
            "image": "\u064a\u062c\u0628 \u0623\u0646 \u064a\u0643\u0648\u0646 \u0627\u0644\u062d\u0642\u0644 :attribute \u0635\u0648\u0631\u0629\u064b",
            "in": "\u0627\u0644\u062d\u0642\u0644 :attribute \u0644\u0627\u063a\u064d",
            "in_array": "\u0627\u0644\u062d\u0642\u0644 :attribute \u063a\u064a\u0631 \u0645\u0648\u062c\u0648\u062f \u0641\u064a :other.",
            "integer": "\u064a\u062c\u0628 \u0623\u0646 \u064a\u0643\u0648\u0646 \u0627\u0644\u062d\u0642\u0644 :attribute \u0639\u062f\u062f\u064b\u0627 \u0635\u062d\u064a\u062d\u064b\u0627",
            "ip": "\u064a\u062c\u0628 \u0623\u0646 \u064a\u0643\u0648\u0646 \u0627\u0644\u062d\u0642\u0644 :attribute \u0639\u0646\u0648\u0627\u0646 IP \u0630\u064a \u0628\u064f\u0646\u064a\u0629 \u0635\u062d\u064a\u062d\u0629",
            "json": "\u064a\u062c\u0628 \u0623\u0646 \u064a\u0643\u0648\u0646 \u0627\u0644\u062d\u0642\u0644 :attribute \u0646\u0635\u0622 \u0645\u0646 \u0646\u0648\u0639 JSON.",
            "max": {
                "numeric": "\u064a\u062c\u0628 \u0623\u0646 \u062a\u0643\u0648\u0646 \u0642\u064a\u0645\u0629 \u0627\u0644\u062d\u0642\u0644 :attribute \u0645\u0633\u0627\u0648\u064a\u0629 \u0623\u0648 \u0623\u0635\u063a\u0631 \u0644\u0640 :max.",
                "file": "\u064a\u062c\u0628 \u0623\u0646 \u0644\u0627 \u064a\u062a\u062c\u0627\u0648\u0632 \u062d\u062c\u0645 \u0627\u0644\u0645\u0644\u0641 :attribute :max \u0643\u064a\u0644\u0648\u0628\u0627\u064a\u062a",
                "string": "\u064a\u062c\u0628 \u0623\u0646 \u0644\u0627 \u064a\u062a\u062c\u0627\u0648\u0632 \u0637\u0648\u0644 \u0627\u0644\u0646\u0651\u0635 :attribute :max \u062d\u0631\u0648\u0641\u064d\/\u062d\u0631\u0641\u064b\u0627",
                "array": "\u064a\u062c\u0628 \u0623\u0646 \u0644\u0627 \u064a\u062d\u062a\u0648\u064a \u0627\u0644\u062d\u0642\u0644 :attribute \u0639\u0644\u0649 \u0623\u0643\u062b\u0631 \u0645\u0646 :max \u0639\u0646\u0627\u0635\u0631\/\u0639\u0646\u0635\u0631."
            },
            "mimes": "\u064a\u062c\u0628 \u0623\u0646 \u064a\u0643\u0648\u0646 \u0627\u0644\u062d\u0642\u0644 \u0645\u0644\u0641\u064b\u0627 \u0645\u0646 \u0646\u0648\u0639 : :values.",
            "mimetypes": "\u064a\u062c\u0628 \u0623\u0646 \u064a\u0643\u0648\u0646 \u0627\u0644\u062d\u0642\u0644 \u0645\u0644\u0641\u064b\u0627 \u0645\u0646 \u0646\u0648\u0639 : :values.",
            "min": {
                "numeric": "\u064a\u062c\u0628 \u0623\u0646 \u062a\u0643\u0648\u0646 \u0642\u064a\u0645\u0629 \u0627\u0644\u062d\u0642\u0644 :attribute \u0645\u0633\u0627\u0648\u064a\u0629 \u0623\u0648 \u0623\u0643\u0628\u0631 \u0644\u0640 :min.",
                "file": "\u064a\u062c\u0628 \u0623\u0646 \u064a\u0643\u0648\u0646 \u062d\u062c\u0645 \u0627\u0644\u0645\u0644\u0641 :attribute \u0639\u0644\u0649 \u0627\u0644\u0623\u0642\u0644 :min \u0643\u064a\u0644\u0648\u0628\u0627\u064a\u062a",
                "string": "\u064a\u062c\u0628 \u0623\u0646 \u064a\u0643\u0648\u0646 \u0637\u0648\u0644 \u0627\u0644\u0646\u0635 :attribute \u0639\u0644\u0649 \u0627\u0644\u0623\u0642\u0644 :min \u062d\u0631\u0648\u0641\u064d\/\u062d\u0631\u0641\u064b\u0627",
                "array": "\u064a\u062c\u0628 \u0623\u0646 \u064a\u062d\u062a\u0648\u064a \u0627\u0644\u062d\u0642\u0644 :attribute \u0639\u0644\u0649 \u0627\u0644\u0623\u0642\u0644 \u0639\u0644\u0649 :min \u0639\u064f\u0646\u0635\u0631\u064b\u0627\/\u0639\u0646\u0627\u0635\u0631"
            },
            "not_in": "\u0627\u0644\u062d\u0642\u0644 :attribute \u0644\u0627\u063a\u064d",
            "numeric": "\u064a\u062c\u0628 \u0639\u0644\u0649 \u0627\u0644\u062d\u0642\u0644 :attribute \u0623\u0646 \u064a\u0643\u0648\u0646 \u0631\u0642\u0645\u064b\u0627",
            "present": "\u064a\u062c\u0628 \u062a\u0642\u062f\u064a\u0645 \u0627\u0644\u062d\u0642\u0644 :attribute",
            "regex": "\u0635\u064a\u063a\u0629 \u0627\u0644\u062d\u0642\u0644 :attribute .\u063a\u064a\u0631 \u0635\u062d\u064a\u062d\u0629",
            "required": "\u0627\u0644\u062d\u0642\u0644 :attribute \u0645\u0637\u0644\u0648\u0628.",
            "required_if": "\u0627\u0644\u062d\u0642\u0644 :attribute \u0645\u0637\u0644\u0648\u0628 \u0641\u064a \u062d\u0627\u0644 \u0645\u0627 \u0625\u0630\u0627 \u0643\u0627\u0646 :other \u064a\u0633\u0627\u0648\u064a :value.",
            "required_unless": "\u0627\u0644\u062d\u0642\u0644 :attribute \u0645\u0637\u0644\u0648\u0628 \u0641\u064a \u062d\u0627\u0644 \u0645\u0627 \u0644\u0645 \u064a\u0643\u0646 :other \u064a\u0633\u0627\u0648\u064a :values.",
            "required_with": "\u0627\u0644\u062d\u0642\u0644 :attribute \u0625\u0630\u0627 \u062a\u0648\u0641\u0651\u0631 :values.",
            "required_with_all": "\u0627\u0644\u062d\u0642\u0644 :attribute \u0625\u0630\u0627 \u062a\u0648\u0641\u0651\u0631 :values.",
            "required_without": "\u0627\u0644\u062d\u0642\u0644 :attribute \u0625\u0630\u0627 \u0644\u0645 \u064a\u062a\u0648\u0641\u0651\u0631 :values.",
            "required_without_all": "\u0627\u0644\u062d\u0642\u0644 :attribute \u0625\u0630\u0627 \u0644\u0645 \u064a\u062a\u0648\u0641\u0651\u0631 :values.",
            "same": "\u064a\u062c\u0628 \u0623\u0646 \u064a\u062a\u0637\u0627\u0628\u0642 \u0627\u0644\u062d\u0642\u0644 :attribute \u0645\u0639 :other",
            "size": {
                "numeric": "\u064a\u062c\u0628 \u0623\u0646 \u062a\u0643\u0648\u0646 \u0642\u064a\u0645\u0629 \u0627\u0644\u062d\u0642\u0644 :attribute \u0645\u0633\u0627\u0648\u064a\u0629 \u0644\u0640 :size",
                "file": "\u064a\u062c\u0628 \u0623\u0646 \u064a\u0643\u0648\u0646 \u062d\u062c\u0645 \u0627\u0644\u0645\u0644\u0641 :attribute :size \u0643\u064a\u0644\u0648\u0628\u0627\u064a\u062a",
                "string": "\u064a\u062c\u0628 \u0623\u0646 \u064a\u062d\u062a\u0648\u064a \u0627\u0644\u0646\u0635 :attribute \u0639\u0644\u0649 :size \u062d\u0631\u0648\u0641\u064d\/\u062d\u0631\u0641\u064b\u0627 \u0628\u0627\u0644\u0638\u0628\u0637",
                "array": "\u064a\u062c\u0628 \u0623\u0646 \u064a\u062d\u062a\u0648\u064a \u0627\u0644\u062d\u0642\u0644 :attribute \u0639\u0644\u0649 :size \u0639\u0646\u0635\u0631\u064d\/\u0639\u0646\u0627\u0635\u0631 \u0628\u0627\u0644\u0638\u0628\u0637"
            },
            "string": "\u064a\u062c\u0628 \u0623\u0646 \u064a\u0643\u0648\u0646 \u0627\u0644\u062d\u0642\u0644 :attribute \u0646\u0635\u0622.",
            "timezone": "\u064a\u062c\u0628 \u0623\u0646 \u064a\u0643\u0648\u0646 :attribute \u0646\u0637\u0627\u0642\u064b\u0627 \u0632\u0645\u0646\u064a\u064b\u0627 \u0635\u062d\u064a\u062d\u064b\u0627",
            "unique": "\u0642\u064a\u0645\u0629 \u0627\u0644\u062d\u0642\u0644 :attribute \u0645\u064f\u0633\u062a\u062e\u062f\u0645\u0629 \u0645\u0646 \u0642\u0628\u0644",
            "uploaded": "\u0641\u0634\u0644 \u0641\u064a \u062a\u062d\u0645\u064a\u0644 \u0627\u0644\u0640 :attribute",
            "url": "\u0635\u064a\u063a\u0629 \u0627\u0644\u0631\u0627\u0628\u0637 :attribute \u063a\u064a\u0631 \u0635\u062d\u064a\u062d\u0629",
            "custom": {
                "attribute-name": {
                    "rule-name": "custom-message"
                }
            },
            "attributes": {
                "name": "\u0627\u0644\u0627\u0633\u0645",
                "username": "\u0627\u0633\u0645 \u0627\u0644\u0645\u064f\u0633\u062a\u062e\u062f\u0645",
                "email": "\u0627\u0644\u0628\u0631\u064a\u062f \u0627\u0644\u0627\u0644\u0643\u062a\u0631\u0648\u0646\u064a",
                "first_name": "\u0627\u0644\u0627\u0633\u0645",
                "last_name": "\u0627\u0633\u0645 \u0627\u0644\u0639\u0627\u0626\u0644\u0629",
                "password": "\u0643\u0644\u0645\u0629 \u0627\u0644\u0633\u0631",
                "password_confirmation": "\u062a\u0623\u0643\u064a\u062f \u0643\u0644\u0645\u0629 \u0627\u0644\u0633\u0631",
                "city": "\u0627\u0644\u0645\u062f\u064a\u0646\u0629",
                "country": "\u0627\u0644\u062f\u0648\u0644\u0629",
                "address": "\u0627\u0644\u0639\u0646\u0648\u0627\u0646",
                "phone": "\u0627\u0644\u0647\u0627\u062a\u0641",
                "mobile": "\u0627\u0644\u062c\u0648\u0627\u0644",
                "age": "\u0627\u0644\u0639\u0645\u0631",
                "sex": "\u0627\u0644\u062c\u0646\u0633",
                "gender": "\u0627\u0644\u0646\u0648\u0639",
                "day": "\u0627\u0644\u064a\u0648\u0645",
                "month": "\u0627\u0644\u0634\u0647\u0631",
                "year": "\u0627\u0644\u0633\u0646\u0629",
                "hour": "\u0633\u0627\u0639\u0629",
                "minute": "\u062f\u0642\u064a\u0642\u0629",
                "second": "\u062b\u0627\u0646\u064a\u0629",
                "title": "\u0627\u0644\u0644\u0642\u0628",
                "content": "\u0627\u0644\u0645\u064f\u062d\u062a\u0648\u0649",
                "description": "\u0627\u0644\u0648\u0635\u0641",
                "excerpt": "\u0627\u0644\u0645\u064f\u0644\u062e\u0635",
                "date": "\u0627\u0644\u062a\u0627\u0631\u064a\u062e",
                "time": "\u0627\u0644\u0648\u0642\u062a",
                "available": "\u0645\u064f\u062a\u0627\u062d",
                "size": "\u0627\u0644\u062d\u062c\u0645"
            }
        },
        "en.template": {
            "myprofile": "My Profile",
            "price": "Price",
            "mycalendar": "My Calendar",
            "changepassword": "Change Password",
            "logout": "Log Out",
            "viewsite": "View Site",
            "globalsearch": "Search for something",
            "dashboard": "Dashboard",
            "banner": "Banner",
            "imagesbanner": "Image banner",
            "videobanner": "Video banner",
            "pages": "Pages",
            "services": "Services",
            "photoalbum": "Photo Album",
            "staticblock": "Static Blocks",
            "blog": "Blog",
            "managenews": "Manage News",
            "news": "News",
            "newscategory": "News Category",
            "menu": "Menu",
            "testimonial": "Testimonial",
            "popupcontent": "Popup Content",
            "leads": "Leads",
            "changeprofile": "Change Profile",
            "home": "Home",
            "managebanner": "Manage Banners",
            "banners": "Banners",
            "managepages": "Manage Pages",
            "addpage": "Add Page",
            "editpage": "Edit Page",
            "manageservices": "Manage Services",
            "Services": "services",
            "addservice": "Add Service",
            "editservice": "Edit Service",
            "managephotoalbums": "Manage Photo Albums",
            "photoalbums": "Photo Albums",
            "editphotoalbum": "Edit Photo Album",
            "name": "Name",
            "email": "Email",
            "personalemail": "Personal Email",
            "timezone": "Time Zone",
            "profilephoto": "Profile Photo",
            "imagerecommendation": "Recommended image size is Height 1080px * Width 1920px, only *.jpg, *.jpeg, *.png, *.gif image formats are supported. Image should be maximum size of 5 MB",
            "updateprofile": "Update Profile",
            "oldpassword": "Old Password",
            "newpassword": "New Password",
            "confirmpassword": "Confirm Password",
            "updatepassword": "Update Password",
            "webhits": "Web Hits",
            "mobilehits": "Mobile Hits",
            "viewmore": "View More",
            "title": "Title",
            "details": "Details",
            "createdatetime": "Created  Date \/ Time",
            "cmspages": "CMS Pages",
            "seeallrecords": "See All Records",
            "norecordsavailable": "No records available",
            "contactuslead": "Contact Us Lead",
            "emailid": "Email ID",
            "faq": "FAQs",
            "newsletterleads": "Newsletter Leads",
            "contact": "Contact",
            "emaillog": "Email Log",
            "rolemanager": "Role Manager",
            "usermanagement": "User Management",
            "team": "Team",
            "recentupdates": "Recent Updates",
            "settings": "Settings",
            "generalsettings": "General Settings",
            "trash": "Trash",
            "logmanager": "Log Manager",
            "filterby": "Filter By",
            "publish": "Publish",
            "unpublish": "Unpublish",
            "selectstatus": "Select Status",
            "selectpage": "Select Page",
            "addbanner": "Add Banner",
            "search": "Search",
            "id": "ID",
            "page": "Page",
            "bannertype": "Banner Type",
            "image": "Image",
            "displayorder": "Display Order",
            "actions": "Actions",
            "back": "Back",
            "homebanner": "Home Banner",
            "innerbanner": "Inner Banner",
            "selectbanner": "Select Banner",
            "homebannerrecomand": "Recommended image size is Height 1900px * Width 900px, only *.jpg, *.jpeg, *.png, *.gif image formats are supported. Image should be maximum size of 5 MB",
            "innerbannerrecomand": "Recommended image size is Height 1900px * Width 500px, only *.jpg, *.jpeg, *.png, *.gif image formats are supported. Image should be maximum size of 5 MB",
            "description": "Description",
            "displayinformation": "Display Information",
            "defaultbanner": "Default Banner",
            "saveandedit": "Save &amp; Keep Editing",
            "saveandexit": "Save &amp; Exit",
            "cancel": "Cancel",
            "editbanner": "Edit Banner",
            "edit": "Edit",
            "display": "Display",
            "yes": "Yes",
            "no": "No",
            "delete": "Delete",
            "confirm": "Confirm",
            "close": "Close",
            "seoinformation": "SEO Information",
            "autogenerate": "Auto Generate",
            "metatitle": "Meta Title",
            "metakeyword": "Meta Keyword",
            "metadescription": "Meta Description",
            "addnewpage": "Add New Page",
            "module": "Module",
            "selectmodule": "Select Module",
            "url": "Url",
            "contents": "Contents",
            "addnewservice": "Add New service",
            "order": "Order",
            "selectimage": "Select Image",
            "serviceimagerecomand": "Recommended image size is Height 700px * Width 500px, only *.jpg, *.jpeg, *.png, *.gif image formats are supported. Image should be maximum size of 5 MB",
            "category": "Category",
            "addphotoalbum": "Add Photo Album",
            "totalimages": "Total Images",
            "gallery": "Gallery",
            "selectphoto": "Select Photo",
            "photoalbumimagerecomand": "Recommended image size is Height 400px * Width 300px, only *.jpg, *.jpeg, *.png, *.gif image formats are supported. Image should be maximum size of 5 MB",
            "addnewphoto": "Add New Photo",
            "addphoto": "Add Photo",
            "save": "Save",
            "uploadyourimages": "Upload your images",
            "photogallery": "Photo Gallery",
            "caption": "Caption",
            "adv_name": "Advertise Name",
            'shortdescription': 'Short Description',
            'phone': 'Phone',
            'designation': 'Designation',
            'video': 'Video',
            'oneRecordDeleted': 'Please select at-least one record to delete',
            'selectedDeleted': 'Are you sure you want to permanently delete this data? You will not able to retrieve it. Press DELETE to confirm.',
            'author': 'Author',
            'category': 'Category',
            'pages': 'Pages',
            'positions': 'Positions',
            'date': 'Date',
            'link': 'Link',
            'startDateTime': 'Start Date Time must be less than End Date Time',
            'endDateTime': 'End Date Time must be greater than Start Date Time',
            'link' : 'Link',
            'question': 'Question',
            'answer': 'Answer',
            'metaTag': 'Meta tag',
            'bannerModule': {
                'bannerValidation': 'Banner',
            },
            'managePopup': {
                'startDateTime': 'Start date and time',
                'endDateTime': 'End date and time'
            },

            'contactModule': {
                'address': 'Address',
                'primary': 'Primary',
                'maxlength': 'You reach the maximum limit.',

            },
            'settingModule': {
                'siteName': 'Site name',
                'defaultAdminEmail': 'Default Admin Email',
                'frontLogo': 'Front Logo',
                'mailerIsRequired': 'Mailer',
                'smtp': 'Host',
                'smtpUserName': 'Smtp username',
                'smtpPassword': 'Smtp password',
                'smtpPort': 'Smtp port',
                'smtpSenderName': 'Smtp sender name',
                'smtpSenderId': 'Smtp sender id',
                'mailContent': 'Email Signature',
                'defaultCurrencySymbol': 'Default currency symbol',
                'googleAnalyticCode': 'Google analytic code',
                'googleTagManger': 'Google tag manager for body',
                'validUrl': 'url',
                'facebookId': 'Facebook id',
                'facebookApi': 'Facebook api',
                'facebookSecretKey': 'Facebook secret key',
                'facebookAccessToken': 'Facebook access token',
                'twitterApi': 'Twitter api',
                'twitterSecretKey': 'Twitter secret key',
                'twitterAccessToken': 'Twitter access token',
                'twitterAccessTokenKey': 'Twitter access token key',
                'linkedinApi': 'Linkedin api',
                'linkedinSecretKey': 'Linkedin secret key',
                'linkedinAccessToken': 'Linkedin access token',
                'linkedinAccessTokenKey': 'Linkedin access token key',
                'googleMapKey': 'Google map key',
                'googleCaptchaKey': 'Google captcha key',
                'resetOption': 'Reset option',
            },
            'userModule': {
                'userName': 'Name',
                'userEmail': 'Email',
                'userPassword': 'Password',
                'confirmPassord': 'Confirm password',
                'userRole': 'Role',
            },
            'sponserModule': {
                'sponserName': 'Name',
            },
        },
        "en.validation": {
            "accepted": " :attribute must be accepted.",
            "active_url": " :attribute is not a valid URL.",
            "after": " :attribute must be a date after :date.",
            "alpha": " :attribute may only contain letters.",
            "alpha_dash": " :attribute may only contain letters, numbers, and dashes.",
            "alpha_num": " :attribute may only contain letters and numbers.",
            "array": " :attribute must be an array.",
            "before": " :attribute must be a date before :date.",
            "between": {
                "numeric": " :attribute must be between :min and :max.",
                "file": " :attribute must be between :min and :max kilobytes.",
                "string": " :attribute must be between :min and :max characters.",
                "array": " :attribute must have between :min and :max items."
            },
            "boolean": " :attribute field must be true or false.",
            "confirmed": " :attribute confirmation does not match.",
            "date": " :attribute is not a valid date.",
            "date_format": " :attribute does not match the format :format.",
            "different": " :attribute and :other must be different.",
            "digits": " :attribute must be :digits digits.",
            "digits_between": " :attribute must be between :min and :max digits.",
            "distinct": " :attribute field has a duplicate value.",
            "email": " :attribute must be a valid email address.",
            "exists": " selected :attribute is invalid.",
            "filled": " :attribute field is required.",
            "image": " :attribute must be an image.",
            "in": " selected :attribute is invalid.",
            "in_array": " :attribute field does not exist in :other.",
            "integer": " :attribute must be an integer.",
            "ip": " :attribute must be a valid IP address.",
            "json": " :attribute must be a valid JSON string.",
            "max": {
                "numeric": " :attribute may not be greater than :max.",
                "file": " :attribute may not be greater than :max kilobytes.",
                "string": " :attribute may not be greater than :max characters.",
                "array": " :attribute may not have more than :max items."
            },
            "mimes": " :attribute must be a file of type: :values.",
            "min": {
                "numeric": " :attribute must be at least :min.",
                "file": " :attribute must be at least :min kilobytes.",
                "string": " :attribute must be at least :min characters.",
                "array": " :attribute must have at least :min items."
            },
            "not_in": " selected :attribute is invalid.",
            "numeric": " :attribute must be a number.",
            "present": " :attribute field must be present.",
            "regex": " :attribute format is invalid.",
            "required": " :attribute field is required.",
            "required_if": " :attribute field is required when :other is :value.",
            "required_unless": " :attribute field is required unless :other is in :values.",
            "required_with": " :attribute field is required when :values is present.",
            "required_with_all": " :attribute field is required when :values is present.",
            "required_without": " :attribute field is required when :values is not present.",
            "required_without_all": " :attribute field is required when none of :values are present.",
            "same": " :attribute and :other must match.",
            "size": {
                "numeric": " :attribute must be :size.",
                "file": " :attribute must be :size kilobytes.",
                "string": " :attribute must be :size characters.",
                "array": " :attribute must contain :size items."
            },
            "string": " :attribute must be a string.",
            "timezone": " :attribute must be a valid zone.",
            "unique": " :attribute has already been taken.",
            "url": " Enter valid :attribute.",
            "minStrict": " :attribute must be a number higher than zero.",
            "number": " :attribute is valid only number.",
            "noSpace": " :attribute field is required.",
            'maxlength': "You reach the maximum limit.",
            "custom": {
                "attribute-name": {
                    "rule-name": "custom-message"
                }
            },
            "attributes": []
        }
    });
})();