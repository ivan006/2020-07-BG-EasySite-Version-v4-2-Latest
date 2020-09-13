version: 4.2

google:
the remove function,                                done
why does it init then processs then init again?,    done
replace if outdated,                                done (name change sticks)
handle user abort jam                               pending
thread only when pending                            done
handle calc imp discrepency Error                   pending
log progress                                        done
rest between calc timeouts                          done
google: VerifyCsrfToken  disable for route laravel
https://stackoverflow.com/questions/31223189/in-laravel-5-how-to-disable-verifycsrftoken-middleware-for-specific-route
https://stackoverflow.com/questions/31729415/disable-csrf-in-laravel-for-specific-route
google: VerifyCsrfToken  except group

https://stackoverflow.com/questions/5687600/jquery-call-ajax-every-10-seconds
google: ajax call every 5 seconds jquery
https://stackoverflow.com/questions/25446628/ajax-jquery-refresh-div-every-5-seconds/25446696
https://stackoverflow.com/questions/5687600/jquery-call-ajax-every-10-seconds

// useful start
https://makitweb.com/how-to-fire-ajax-request-on-regular-interval/
// useful end
https://www.w3schools.com/jquery/jquery_get_started.asp
google: get value of div jquery
https://stackoverflow.com/questions/1827659/get-a-div-value-in-jquery
https://stackoverflow.com/questions/1570905/use-jquery-to-set-value-of-div-tag
https://stackoverflow.com/questions/6552959/sum-of-two-input-value-by-jquery
https://stackoverflow.com/questions/13953939/how-to-force-addition-instead-of-concatenation-in-javascript
// usefull start
https://stackoverflow.com/questions/3938534/download-file-to-server-from-url
// usefull end
google: php make dir
https://stackoverflow.com/questions/369602/deleting-an-element-from-an-array-in-php
https://stackoverflow.com/questions/9215588/break-out-of-if-and-foreach
https://stackoverflow.com/questions/1936237/php-convert-date-into-seconds
https://www.php.net/strtotime
google: check if dir exists php
google: result abreviated
google: excempt route group from csrf
https://laravel-news.com/excluding-routes-from-the-csrf-middleware
https://stackoverflow.com/questions/4535888/jquery-text-and-newlines
https://en.wikipedia.org/wiki/Box-drawing_character
https://stackoverflow.com/questions/2109325/how-do-i-strip-all-spaces-out-of-a-string-in-php/2109339
google: exec rm loop php
https://stackoverflow.com/questions/4594180/deleting-all-files-from-a-folder-using-php
https://stackoverflow.com/questions/3338123/how-do-i-recursively-delete-a-directory-and-its-entire-contents-files-sub-dir
https://www.dropbox.com/developers/reference/webhooks
https://www.dropbox.com/developers/documentation/http/documentation#files-list_folder-continue
google: what exaactly does the "get the latest changes for a user" part of "Typically, the code you run in response to a notification will make a call to /files/list_folder/continue to get the latest changes for a user. " mean?
https://www.dropbox.com/developers/documentation/http/documentation#files-list_folder-continue

https://www.dropboxforum.com/t5/Discuss-Dropbox-Developer-API/Get-App-name-from-DROPBOX-API/td-p/324984
google: creat file and set timestamp php
https://www.php.net/manual/en/function.touch.php
https://www.w3schools.com/php/phptryit.asp?filename=tryphp_func_time
https://www.w3schools.com/php/func_date_time.asp
https://www.w3schools.com/php/func_filesystem_filemtime.asp
https://tryphp.w3schools.com/showphp.php?filename=demo_func_filesystem_filemtime
https://www.w3schools.com/php/func_date_strtotime.asp
google; stringtime php dropbox
google: dropbox time format
google: 2020-07-16T20:24:12Z
https://www.w3schools.com/php/func_date_time.asp
google: 2020-07-17T08:13:03Z
https://www.dropboxforum.com/t5/Dropbox-installs-integrations/Time-Zone-Issue-while-uploading-to-the-Dropbox-website/td-p/181117
https://www.dropbox.com/account
google: sa timezone
https://www.dropboxforum.com/t5/Dropbox-accounts-billing/Trouble-with-setting-time-zone/td-p/207699
google: strcuture instanly (not using 2 stage dropbox structure assessing)
google: give 1 value if folder not explored and 0 if explored
google: get all element that have specific valu php
google: php elements with same value
https://www.php.net/manual/en/function.array-intersect.php
https://stackoverflow.com/questions/19966490/find-all-array-keys-that-has-same-value
https://www.w3schools.com/php/func_array_filter.asp
google: - if empty scan root if has content scan first unexplored folder if all are explored conclude process
- on scan explore folder and then if its not the root then mark folder as explored all sub folders mark as unexaplored
- save to remote state log
- if tired rest
- repeat
# 2
- shallow scan
- log
- check pulse
- go deeper and repeat
#3
- process state
- data state
#4
- read remote state log
- scan unexplored items
- write remote state log
- check pulse
- repeat
https://www.w3schools.com/php/func_array_search.asp
https://stackoverflow.com/questions/20017409/how-to-solve-php-error-notice-array-to-string-conversion-in
google: set prop php as array
https://stackoverflow.com/questions/33389938/class-properties-as-array-instead-of-variables
https://stackoverflow.com/questions/804850/get-php-class-property-by-string
google:
dropbox_sync_state_mngr
	process_queue
		occupied
		vacant
	process_stager (mode/steps)
		standby
		calculation
		calculation_standby
		//calculation_rest
		implementation
		implementation_standby
		//implementation_rest
	data_compararer
		dropbox
		local
		diff
google: repeat process php
https://stackoverflow.com/questions/15040391/repeat-php-code-or-script
google: if is not both php
https://stackoverflow.com/questions/35270321/php-if-statement-either-but-not-both/35270392
google: xand php
https://www.w3resource.com/php/statement/break.php
https://stackoverflow.com/questions/9353240/php-switch-statement-inside-another-switch-statement
google: it use to work like
standby
//reception		if nothing else
calculation_rest	if diff is null and proc promise open and proc time is 80+
calculation		if diff is null and proc promise open
calculation_standby	if diff is null and proc promise closed
implementation_rest	if remote state is not {} or webhook is pending and init promise is open and  and proc time is 80+
implementation		if remote state is not {} or webhook is pending and init promise is open
implementation_standby	if remote state is not {} or webhook is pending and init promise is closed
google: stop interval ajax based on responce
https://makitweb.com/how-to-fire-ajax-request-on-regular-interval/
https://stackoverflow.com/questions/5687600/jquery-call-ajax-every-10-seconds
https://api.jquery.com/jquery.ajax/
https://stackoverflow.com/questions/18266718/conditional-if-statement-after-ajax-post-using-response-is-this-ok
google: stop recuring ajax if
google: repeat ajax call until responce
http://www.queryadmin.com/2283/repeat-ajax-get-until-a-specific-response-is-met-in-jquery/
https://www.codegrepper.com/code-examples/javascript/jquery+wait+for+all+ajax+requests+to+complete
google: repeat ajax call until specific response javascript
google: pass argument to setinvertval function
https://stackoverflow.com/questions/10872006/how-do-i-change-the-value-of-a-global-variable-inside-of-a-function
https://www.javatpoint.com/javascript-global-variable
google: global variable not updatabe
https://stackoverflow.com/questions/14731040/unable-to-update-global-variable-in-javascript-html-function
google: stop setinteval
https://stackoverflow.com/questions/109086/stop-setinterval-call-in-javascript
https://www.w3schools.com/jsref/met_win_clearinterval.asp
https://www.w3schools.com/jsref/tryit.asp?filename=tryjsref_win_clearinterval
https://electrictoolbox.com/check-php-script-already-running/
https://www.sitepoint.com/proc-open-communicate-with-the-outside-world/
google: inter process ipc promises
https://www.w3schools.com/php/func_misc_connection_aborted.asp
https://stackoverflow.com/questions/2385047/when-will-destruct-not-be-called-in-php
https://stackoverflow.com/questions/13499399/explanation-of-register-shutdown-function

// network version start
google: laravel file manager
google: laravel file manager user permissions
https://github.com/UniSharp/laravel-filemanager/issues/322
https://github.com/alexusmai/laravel-file-manager/issues/27
https://github.com/alexusmai/laravel-file-manager/blob/master/docs/acl.md
google: nextcoud dropbox youtube
https://www.linuxbabe.com/ubuntu/install-nextcloud-ubuntu-20-04-apache-lamp-stack
https://nextcloud.com/install/#instructions-server
https://help.nextcloud.com/t/no-permissions-to-configure-external-storage/29411
https://zapier.com/blog/dropbox-vs-google-drive/
https://help.nextcloud.com/t/message-smbclient-is-not-installed/5790
https://pydio.com/en/docs/v8/accessing-remote-servers-ftp-sftp-smb-webdav-dropbox
https://superuser.com/questions/95872/sambawindows-allow-multiple-connections-by-different-users
https://lists.samba.org/archive/samba/2007-February/129636.html
google: multiple webdav
google: web app file manager
google: multiple dropbox website integrations
https://owncloud.org/news/external-storage-dropbox/
googe: external storage integration dropbox
// network version end
images
https://stackoverflow.com/questions/36066144/how-should-i-serve-an-image-with-laravel/36477345
https://stackoverflow.com/questions/56747096/class-app-file-not-found-in-laravel-5-8/56747146
google: Image source not readable
https://stackoverflow.com/questions/36157824/intervention-image-upload-error-image-source-not-readable
https://stackoverflow.com/questions/9743536/best-way-to-encode-a-path-to-be-used-as-html-valid-id-or-name-tag
https://stackoverflow.com/questions/16334310/display-the-current-git-version-in-php
https://stackoverflow.com/questions/52439209/image-resize-laravel
