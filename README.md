# eClass Integrated Platform 2.5 API (for CHKL)

#### Login

`u` `p`

#### Logout

`token`

#### Announcement

`token` `type` public/group

#### Announcement Download

`token` `file`

#### Announcement View

`token` `mid`

#### Class Login

`token` `cid`

#### Class List

`token`

#### Class Announcement

`token`

#### Class Assignment

`token`

#### Class Recent Note

`token`

#### Imail

`token` `page` 1,2,3

#### Imail View

`token` `mid`

#### Calendar Event

`token` `type` today/month

### BUG fix
> Viewpager Auto focus (Listview, RecycleView, Webview) Problems

https://stackoverflow.com/questions/36923948/recycleview-steals-focus-when-inside-a-nestedscrollview

> ListView Auto height 100% with custom adapter

https://stackoverflow.com/questions/17693578/android-how-to-display-2-listviews-in-one-activity-one-after-the-other

### Scrapper Note
> Class List [GET]

<site>/home/index20_aj_eclass.php

> Change/View Classes [GET]

<site>/home/eLearning/login.php?uc_id=405577

> Public Announcement [GET]

<site>/home/moreannouncement.php?type=0

> Group Announcement [GET]

<site>/home/moreannouncement.php?type=1

> Course Announcement [GET]

<site>/eclass40/src/portal/announcement/announcement_full_list.php?clearCoo=1

> Course Recent note [POST]

<site>/eclass40/src/dialog_ajax.php?new_type=get_history_lesson_and_notes&access_assessment=1

> Course Redo List [POST]

<site>/eclass40/src/index_edit_ajax.php?ACTION=get_assessment_redo_list

> Course Assignment List [POST]

<site>/eclass40/src/index_edit_ajax.php?ACTION=get_assessment_list

> Calendar Month Event [GET]

<site>/home/index20_aj_event.php?type=1&ts=

> Calendar Today Event [GET]

<site>/home/index20_aj_event.php?type=0&ts=
