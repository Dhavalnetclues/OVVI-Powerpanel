<?php

namespace App\Helpers;

use File;
use Request;

class FrontPageContent_Shield
{

    public static $assets = array();

    public static function renderBuilder($data)
    {
        $response = '';

        Self::$assets['js']['lib'] = array();
        Self::$assets['js']['modulejs'] = array();

        if (!empty($data)) {

            $data = json_decode($data, true);

            if (is_array($data)) {
                $i = 0;
                $two = 1;
                $three = 1;
                $four = 1;

                foreach ($data as $section) {

                    if (isset($section['val']['module']) && $section['val']['module'] != '') {
                        $module = $section['val']['module'];
                    } else {
                        $module = isset($section['type']) ? $section['type'] : '';
                    }

                    if ($module == 'only_title') {
                        $content = $section['val']['content'];
                        $extclass = '';
                        $response .= Self::OnlyTitleHTML($content, $extclass);
                    } else if ($module == 'iframe') {
                        $content = html_entity_decode($section['val']['content']);
                        $extclass = isset($section['val']['extclass']) ? $section['val']['extclass'] : '';
                        $response .= Self::OnlyIframeHTML($content, $extclass);
                    } else if ($module == 'partitondata') {
                        if ($section['partitionclass'] == 'TwoColumns') {
                            $content = $section['val'];
                            $type = $section['gentype'];
                            $subtype = $section['subtype'];
                            $partitionclass = $section['partitionclass'];
                            $response .= Self::TwoColumnsHTML($content, $type, $subtype, $partitionclass, $two);
                            $two++;
                            if ($two > 2) {
                                $two = 1;
                            }
                        }
                        if ($section['partitionclass'] == 'ThreeColumns') {

                            $content = $section['val'];
                            $type = $section['gentype'];
                            $subtype = $section['subtype'];
                            $partitionclass = $section['partitionclass'];
                            $response .= Self::ThreeColumnsHTML($content, $type, $subtype, $partitionclass, $three);
                            $three++;
                            if ($three > 3) {
                                $three = 1;
                            }

                        }
                        if ($section['partitionclass'] == 'OneThreeColumns') {
                            $content = $section['val'];
                            $type = $section['gentype'];
                            $subtype = $section['subtype'];
                            $partitionclass = $section['partitionclass'];
                            $response .= Self::OneThreeColumnsHTML($content, $type, $subtype, $partitionclass, $two);
                            $two++;
                            if ($two > 2) {
                                $two = 1;
                            }
                        }
                        if ($section['partitionclass'] == 'ThreeOneColumns') {
                            $content = $section['val'];
                            $type = $section['gentype'];
                            $subtype = $section['subtype'];
                            $partitionclass = $section['partitionclass'];
                            $response .= Self::ThreeOneColumnsHTML($content, $type, $subtype, $partitionclass, $two);
                            $two++;
                            if ($two > 2) {
                                $two = 1;
                            }
                        }
                        if ($section['partitionclass'] == 'FourColumns') {
                            $content = $section['val'];

                            $type = $section['gentype'];
                            $subtype = $section['subtype'];
                            $partitionclass = $section['partitionclass'];
                            $response .= Self::FourColumnsHTML($content, $type, $subtype, $partitionclass, $four);
                            $four++;
                            if ($four > 4) {
                                $four = 1;
                            }
                        }
                    } else if ($module == 'formarea') {
                        $formid = $section['val']['id'];
                        $content = $section['val']['content'];
                        $extclass = '';
                        $response .= Self::OnlyFormBuilderHTML($formid, $content, $extclass);
                    } else if ($module == 'image') {
                        $title = $section['val']['title'];
                        $image = $section['val']['image'];
                        $alignment = $section['val']['alignment'];
                        $img = $section['val']['src'];
                        $extraclass = $section['val']['extra_class'];
                        $response .= Self::ImageHTML($title, $img, $image, $alignment, $extraclass);
                    } else if ($module == 'document') {
                        $document = $section['val']['document'];
                        $img = $section['val']['src'];
                        $response .= Self::DocumentHTML($section['val']);
                    } else if ($module == 'textarea') {
                        $content = $section['val']['content'];
                        $class = isset($section['val']['extclass']) ? $section['val']['extclass'] : '';
                        $response .= Self::OnlyContentHTML($content, $class);
                    } else if ($module == 'twocontent') {
                        $leftcontent = $section['val']['leftcontent'];
                        $rightcontent = $section['val']['rightcontent'];
                        $response .= Self::TwoContentHTML($leftcontent, $rightcontent);
                    } else if ($module == 'only_video') {
                        $title = $section['val']['title'];
                        $videoType = $section['val']['videoType'];
                        $vidId = $section['val']['vidId'];
                        $response .= Self::VideoHTML($title, $videoType, $vidId);
                    } else if ($module == 'video_content') {
                        $title = $section['val']['title'];
                        $videoType = $section['val']['videoType'];
                        $vidId = $section['val']['vidId'];
                        $content = $section['val']['content'];
                        $alignment = $section['val']['alignment'];
                        $response .= Self::VideoContentHTML($title, $videoType, $vidId, $content, $alignment);
                    } else if ($module == 'spacer_template') {
                        $config = $section['val']['config'];
                        $response .= Self::SpacerHTML($config);
                    } else if ($module == 'img_content') {
                        $title = $section['val']['title'];
                        $content = $section['val']['content'];
                        $alignment = $section['val']['alignment'];
                        $image = $section['val']['image'];
                        $src = $section['val']['src'];
                        $response .= Self::ContentHTML($title, $content, $alignment, $src, $image);

                    } else if ($module == 'row_template') {

                        $content = $section;
                        if (isset($section1['filter']) && !empty($section1['filter'])) {
                            $filter = $section1['filter'];
                        } else {
                            $filter = false;
                        }
                        $response .= Self::rowHTML($content, $filter);

                    } else if ($module == 'interconnections_template') {
                        $title = $section['val']['title'];
                        $parentorg = $section['val']['parentorg'];
                        $orgclass = $section['val']['orgclass'];
                        $filter = $section['val']['template'];
                        if (isset($section['val']['filter']) && !empty($section['val']['filter'])) {
                            $dbFilter = $section['val']['filter'];
                        } else {
                            $dbFilter = false;
                        }
                        $response .= Self::interconnectionsHTML($title, $parentorg, $orgclass, $filter, $dbFilter);
                    } else if ($module == 'organizations_template') {
                        $title = $section['val']['title'];
                        $parentorg = $section['val']['parentorg'];
                        $orgclass = $section['val']['orgclass'];
                        $filter = $section['val']['template'];
                        $response .= Self::organizationsHTML($title, $parentorg, $orgclass, $filter);
                    } else if ($module == 'team') {

                        $title = $section['val']['title'];
                        if (isset($section['val']['desc']) && $section['val']['desc'] != '') {
                            $desc = $section['val']['desc'];
                        } else {
                            $desc = '';
                        }
                        if (isset($section['val']['extraclass']) && $section['val']['extraclass'] != '') {
                            $class = $section['val']['extraclass'];
                        } else {
                            $class = '';
                        }
                        if (isset($section['val']['config']) && $section['val']['config'] != '') {
                            $config = $section['val']['config'];
                        } else {
                            $config = '';
                        }
                        $records = $section['val']['records'];
                        $filter = $section['val']['template'];
                        $layout = $section['val']['layout'];
                        $recIds = array_column($records, 'id');
                        $fill = \Powerpanel\Team\Models\Team::getBuilderTeam($recIds);

                        if ($fill->count() > 0) {
                            $owlCaurosolLib = 'assets/libraries/owl.carousel/js/owl.carousel.min.js';

                            if (!in_array($owlCaurosolLib, Self::$assets['js']['lib'])) {

                                Self::$assets['js']['lib'][] = $owlCaurosolLib;
                            }

                            $owlCaurosolLib1 = 'assets/libraries/libraries-update/owl.carousel/js/owl.carousel-update.js';

                            if (!in_array($owlCaurosolLib1, Self::$assets['js']['lib'])) {

                                Self::$assets['js']['lib'][] = $owlCaurosolLib1;
                            }

                            $owlCaurosolLib2 = 'assets/js/index.js';

                            if (!in_array($owlCaurosolLib2, Self::$assets['js']['lib'])) {

                                Self::$assets['js']['lib'][] = $owlCaurosolLib2;
                            }

                            if ($module != 'team') {

                                $moduleJS = 'assets/js/team.js';

                                if (!in_array($moduleJS, Self::$assets['js']['modulejs'])) {

                                    Self::$assets['js']['modulejs'][] = $moduleJS;
                                }
                            }
                        }

                        $response .= Self::teamHTML($title, $desc, $class, $config, $records, $filter, $layout);
                    } else if ($module == 'boardofdirectors') {

                        $title = $section['val']['title'];
                        if (isset($section['val']['desc']) && $section['val']['desc'] != '') {
                            $desc = $section['val']['desc'];
                        } else {
                            $desc = '';
                        }
                        if (isset($section['val']['extraclass']) && $section['val']['extraclass'] != '') {
                            $class = $section['val']['extraclass'];
                        } else {
                            $class = '';
                        }
                        if (isset($section['val']['config']) && $section['val']['config'] != '') {
                            $config = $section['val']['config'];
                        } else {
                            $config = '';
                        }
                        $records = $section['val']['records'];
                        $filter = $section['val']['template'];
                        $layout = $section['val']['layout'];
                        $recIds = array_column($records, 'id');
                        $fill = \Powerpanel\BoardOfDirectors\Models\BoardOfDirectors::getBuilderBoard($recIds);

                        if ($fill->count() > 0) {
                            $owlCaurosolLib = 'assets/libraries/owl.carousel/js/owl.carousel.min.js';

                            if (!in_array($owlCaurosolLib, Self::$assets['js']['lib'])) {

                                Self::$assets['js']['lib'][] = $owlCaurosolLib;
                            }

                            $owlCaurosolLib1 = 'assets/libraries/libraries-update/owl.carousel/js/owl.carousel-update.js';

                            if (!in_array($owlCaurosolLib1, Self::$assets['js']['lib'])) {

                                Self::$assets['js']['lib'][] = $owlCaurosolLib1;
                            }

                            $owlCaurosolLib2 = 'assets/js/index.js';

                            if (!in_array($owlCaurosolLib2, Self::$assets['js']['lib'])) {

                                Self::$assets['js']['lib'][] = $owlCaurosolLib2;
                            }

                            if ($module != 'boardofdirectors') {

                                $moduleJS = 'assets/js/boardofdirectors.js';

                                if (!in_array($moduleJS, Self::$assets['js']['modulejs'])) {

                                    Self::$assets['js']['modulejs'][] = $moduleJS;
                                }
                            }
                        }

                        $response .= Self::boardofdirectorsHTML($title, $desc, $class, $config, $records, $filter, $layout);
                    } else if ($module == 'publication') {

                        $title = $section['val']['title'];
                        if (isset($section['val']['desc']) && $section['val']['desc'] != '') {
                            $desc = $section['val']['desc'];
                        } else {
                            $desc = '';
                        }
                        if (isset($section['val']['extraclass']) && $section['val']['extraclass'] != '') {
                            $class = $section['val']['extraclass'];
                        } else {
                            $class = '';
                        }
                        if (isset($section['val']['config']) && $section['val']['config'] != '') {
                            $config = $section['val']['config'];
                        } else {
                            $config = '';
                        }
                        if (isset($section['val']['layout']) && $section['val']['layout'] != '') {
                            $layout = $section['val']['layout'];
                        } else {
                            $layout = '';
                        }
                        if (isset($section['val']['template']) && $section['val']['template'] != '') {
                            $filter = $section['val']['template'];
                        } else {
                            $filter = '';
                        }
                        $records = $section['val']['records'];

                        $recIds = array_column($records, 'id');
                        $fill = \Powerpanel\Publications\Models\Publications::getBuilderPublication($recIds);
                        if ($fill->count() > 0) {
                            $owlCaurosolLib = 'assets/libraries/owl.carousel/js/owl.carousel.min.js';

                            if (!in_array($owlCaurosolLib, Self::$assets['js']['lib'])) {

                                Self::$assets['js']['lib'][] = $owlCaurosolLib;
                            }

                            $owlCaurosolLib1 = 'assets/libraries/libraries-update/owl.carousel/js/owl.carousel-update.js';

                            if (!in_array($owlCaurosolLib1, Self::$assets['js']['lib'])) {

                                Self::$assets['js']['lib'][] = $owlCaurosolLib1;
                            }

                            $owlCaurosolLib2 = 'assets/js/index.js';

                            if (!in_array($owlCaurosolLib2, Self::$assets['js']['lib'])) {

                                Self::$assets['js']['lib'][] = $owlCaurosolLib2;
                            }

                            if ($module != 'publication') {

                                $moduleJS = 'assets/js/publication.js';

                                if (!in_array($moduleJS, Self::$assets['js']['modulejs'])) {

                                    Self::$assets['js']['modulejs'][] = $moduleJS;
                                }
                            }
                        }
                        $response .= Self::publicationHTML($title, $desc, $class, $config, $records, $filter, $layout);
                    } else if ($module == 'complaint-services') {

                        $title = $section['val']['title'];
                        if (isset($section['val']['desc']) && $section['val']['desc'] != '') {
                            $desc = $section['val']['desc'];
                        } else {
                            $desc = '';
                        }
                        if (isset($section['val']['extraclass']) && $section['val']['extraclass'] != '') {
                            $class = $section['val']['extraclass'];
                        } else {
                            $class = '';
                        }
                        if (isset($section['val']['config']) && $section['val']['config'] != '') {
                            $config = $section['val']['config'];
                        } else {
                            $config = '';
                        }
                        $records = $section['val']['records'];
                        $filter = $section['val']['template'];
                        $layout = $section['val']['layout'];
                        $recIds = array_column($records, 'id');
                        $fill = \Powerpanel\ComplaintServices\Models\ComplaintServices::getBuilderComplaintServices($recIds);
                        if ($fill->count() > 0) {
                            $owlCaurosolLib = 'assets/libraries/owl.carousel/js/owl.carousel.min.js';

                            if (!in_array($owlCaurosolLib, Self::$assets['js']['lib'])) {

                                Self::$assets['js']['lib'][] = $owlCaurosolLib;
                            }

                            $owlCaurosolLib1 = 'assets/libraries/libraries-update/owl.carousel/js/owl.carousel-update.js';

                            if (!in_array($owlCaurosolLib1, Self::$assets['js']['lib'])) {

                                Self::$assets['js']['lib'][] = $owlCaurosolLib1;
                            }

                            $owlCaurosolLib2 = 'assets/js/index.js';

                            if (!in_array($owlCaurosolLib2, Self::$assets['js']['lib'])) {

                                Self::$assets['js']['lib'][] = $owlCaurosolLib2;
                            }

                            if ($module != 'complaint-services') {

                                $moduleJS = 'assets/js/complaint-services.js';

                                if (!in_array($moduleJS, Self::$assets['js']['modulejs'])) {

                                    Self::$assets['js']['modulejs'][] = $moduleJS;
                                }
                            }
                        }
                        $response .= Self::complaintservicesHTML($title, $desc, $class, $config, $records, $filter, $layout);
                    } else if ($module == 'team_template') {

                        $title = $section['val']['title'];
                        $config = $section['val']['config'];
                        $class = $section['val']['extclass'];
                        $layout = $section['val']['layout'];
                        $filter = $section['val']['template'];
                        if (isset($section1['val']['filter']) && !empty($section1['val']['filter'])) {
                            $dbFilter = $section1['val']['filter'];
                        } else {
                            $dbFilter = false;
                        }

                        $response .= Self::AllteamHTML($title, $config, $layout, $class, $filter, $dbFilter);
                    } else if ($module == 'boardofdirectors_template') {

                        $title = $section['val']['title'];
                        $config = $section['val']['config'];
                        $class = '';
                        $layout = $section['val']['layout'];
                        $filter = $section['val']['template'];
                        if (isset($section['val']['filter']) && !empty($section['val']['filter'])) {
                            $dbFilter = $section['val']['filter'];
                        } else {
                            $dbFilter = false;
                        }
                        $response .= Self::allBoardOfDirectorsHTML($title, $config, $layout, $class, $filter, $dbFilter);

                    } else if ($module == 'complaintservices_template') {

                        $title = $section['val']['title'];
                        $config = $section['val']['config'];
                        $class = $section['val']['class'];
                        $desc = $section['val']['desc'];
                        $layout = $section['val']['layout'];
                        $filter = $section['val']['template'];
                        if (isset($section['val']['filter']) && !empty($section['val']['filter'])) {
                            $dbFilter = $section['val']['filter'];
                        } else {
                            $dbFilter = false;
                        }
                        $response .= Self::allComplaintServicesHTML($title, $config, $layout, $class, $desc, $filter, $dbFilter);
                    } else if ($module == 'department') {
                        $title = $section['val']['title'];
                        $records = $section['val']['records'];
                        $filter = $section['val']['template'];
                        $extraclass = $section['val']['extraclass'];
                        $response .= Self::departmentHTML($title, $records, $filter, $extraclass);
                    } else if ($module == 'department_template') {
                        $title = $section['val']['title'];
                        $limit = $section['val']['limit'];
                        $sdate = $section['val']['sdate'];
                        $edate = $section['val']['edate'];
                        $class = $section['val']['class'];
                        $filter = $section['val']['template'];
                        $response .= Self::AlldepartmentHTML($title, $limit, $sdate, $edate, $class, $filter);
                    } else if ($module == 'events') {
                        $title = $section['val']['title'];
                        $config = $section['val']['config'];
                        $desc = $section['val']['desc'];
                        $layout = $section['val']['layout'];
                        $records = $section['val']['records'];
                        $filter = $section['val']['template'];
                        $extraclass = $section['val']['extraclass'];
                        $today = date('d-m-y H:i:s');
                        $recIds = array_column($records, 'id');
                        $fields = Self::selectFields($config);
                        $fill = \Powerpanel\Events\Models\Events::getBuilderEvents($fields, $recIds);
                        if ($fill->count() > 0) {
                            $owlCaurosolLib = 'assets/libraries/owl.carousel/js/owl.carousel.min.js';

                            if (!in_array($owlCaurosolLib, Self::$assets['js']['lib'])) {

                                Self::$assets['js']['lib'][] = $owlCaurosolLib;
                            }

                            $owlCaurosolLib1 = 'assets/libraries/libraries-update/owl.carousel/js/owl.carousel-update.js';

                            if (!in_array($owlCaurosolLib1, Self::$assets['js']['lib'])) {

                                Self::$assets['js']['lib'][] = $owlCaurosolLib1;
                            }

                            $owlCaurosolLib2 = 'assets/js/index.js';

                            if (!in_array($owlCaurosolLib2, Self::$assets['js']['lib'])) {

                                Self::$assets['js']['lib'][] = $owlCaurosolLib2;
                            }

                            if ($module != 'events') {

                                $moduleJS = 'assets/js/events.js';

                                if (!in_array($moduleJS, Self::$assets['js']['modulejs'])) {

                                    Self::$assets['js']['modulejs'][] = $moduleJS;
                                }
                            }
                        }
                        $response .= Self::eventHTML($title, $desc, $config, $layout, $records, $filter, $extraclass, $today);
                    } else if ($module == 'events_template') {
                        $title = $section['val']['title'];
                        $limit = $section['val']['limit'];
                        $desc = $section['val']['desc'];
                        $config = $section['val']['config'];
                        $layout = $section['val']['layout'];
                        $filter = $section['val']['template'];
                        $today = date('d-m-y H:i:s');
                        if (isset($section1['val']['filter']) && !empty($section1['val']['filter'])) {
                            $dbFilter = $section1['val']['filter'];
                        } else {
                            $dbFilter = false;
                        }
                        if (isset($section['val']['class']) && $section['val']['class'] != '') {
                            $class = $section['val']['class'];
                        } else {
                            $class = '';
                        }
                        if (isset($section['val']['sdate']) && $section['val']['sdate'] != '') {
                            $sdate = $section['val']['sdate'];
                        } else {
                            $sdate = '';
                        }
                        if (isset($section['val']['edate']) && $section['val']['edate'] != '') {
                            $edate = $section['val']['edate'];
                        } else {
                            $edate = '';
                        }
                        if (isset($section['val']['eventscat']) && $section['val']['eventscat'] != '') {
                            $eventscat = $section['val']['eventscat'];
                        } else {
                            $eventscat = '';
                        }
                        $response .= Self::AlleventsHTML($title, $limit, $desc, $config, $layout, $filter, $class, $sdate, $edate, $eventscat, $today, $dbFilter);
                    } else if ($module == 'blogs') {
                        $title = $section['val']['title'];
                        $config = $section['val']['config'];
                        $desc = $section['val']['desc'];
                        $layout = $section['val']['layout'];
                        $records = $section['val']['records'];
                        $filter = $section['val']['template'];
                        $extraclass = $section['val']['extraclass'];
                        $recIds = array_column($records, 'id');
                        $fields = Self::selectFields($config);
                        $fill = \Powerpanel\Blogs\Models\Blogs::getBuilderBlog($fields, $recIds);
                        if ($fill->count() > 0) {
                            $owlCaurosolLib = 'assets/libraries/owl.carousel/js/owl.carousel.min.js';

                            if (!in_array($owlCaurosolLib, Self::$assets['js']['lib'])) {

                                Self::$assets['js']['lib'][] = $owlCaurosolLib;
                            }

                            $owlCaurosolLib1 = 'assets/libraries/libraries-update/owl.carousel/js/owl.carousel-update.js';

                            if (!in_array($owlCaurosolLib1, Self::$assets['js']['lib'])) {

                                Self::$assets['js']['lib'][] = $owlCaurosolLib1;
                            }

                            $owlCaurosolLib2 = 'assets/js/index.js';

                            if (!in_array($owlCaurosolLib2, Self::$assets['js']['lib'])) {

                                Self::$assets['js']['lib'][] = $owlCaurosolLib2;
                            }

                            if ($module != 'blogs') {

                                $moduleJS = 'assets/js/blogs.js';

                                if (!in_array($moduleJS, Self::$assets['js']['modulejs'])) {

                                    Self::$assets['js']['modulejs'][] = $moduleJS;
                                }
                            }
                        }
                        $response .= Self::blogsHTML($title, $desc, $config, $layout, $records, $filter, $extraclass);
                    } else if ($module == 'blogs_template') {
                        $title = $section['val']['title'];
                        $limit = $section['val']['limit'];
                        $desc = $section['val']['desc'];
                        $config = $section['val']['config'];
                        $layout = $section['val']['layout'];
                        $filter = $section['val']['template'];
                        if (isset($section['val']['class']) && $section['val']['class'] != '') {
                            $class = $section['val']['class'];
                        } else {
                            $class = '';
                        }
                        if (isset($section['val']['sdate']) && $section['val']['sdate'] != '') {
                            $sdate = $section['val']['sdate'];
                        } else {
                            $sdate = '';
                        }
                        if (isset($section['val']['edate']) && $section['val']['edate'] != '') {
                            $edate = $section['val']['edate'];
                        } else {
                            $edate = '';
                        }
                        if (isset($section['val']['blogscat']) && $section['val']['blogscat'] != '') {
                            $blogscat = $section['val']['blogscat'];
                        } else {
                            $blogscat = '';
                        }
                        $response .= Self::AllblogsHTML($title, $limit, $desc, $config, $layout, $filter, $class, $sdate, $edate, $blogscat);
                    } else if ($module == 'service') {
                        $title = $section['val']['title'];
                        $config = $section['val']['config'];
                        if (isset($section['val']['desc']) && $section['val']['desc'] != '') {
                            $desc = $section['val']['desc'];
                        } else {
                            $desc = '';
                        }
                        $layout = $section['val']['layout'];
                        $records = $section['val']['records'];
                        $filter = $section['val']['template'];
                        if (isset($section['val']['extraclass']) && $section['val']['extraclass'] != '') {
                            $extraclass = $section['val']['extraclass'];
                        } else {
                            $extraclass = '';
                        }
                        $recIds = array_column($records, 'id');
                        $fields = Self::selectFields($config);
                        $fill = \Powerpanel\Services\Models\Services::getServiceList($fields, $recIds, 5);
                        if ($fill->count() > 0) {
                            $owlCaurosolLib = 'assets/libraries/owl.carousel/js/owl.carousel.min.js';

                            if (!in_array($owlCaurosolLib, Self::$assets['js']['lib'])) {

                                Self::$assets['js']['lib'][] = $owlCaurosolLib;
                            }

                            $owlCaurosolLib1 = 'assets/libraries/libraries-update/owl.carousel/js/owl.carousel-update.js';

                            if (!in_array($owlCaurosolLib1, Self::$assets['js']['lib'])) {

                                Self::$assets['js']['lib'][] = $owlCaurosolLib1;
                            }

                            $owlCaurosolLib2 = 'assets/js/index.js';

                            if (!in_array($owlCaurosolLib2, Self::$assets['js']['lib'])) {

                                Self::$assets['js']['lib'][] = $owlCaurosolLib2;
                            }

                            if ($module != 'service') {

                                $moduleJS = 'assets/js/service.js';

                                if (!in_array($moduleJS, Self::$assets['js']['modulejs'])) {

                                    Self::$assets['js']['modulejs'][] = $moduleJS;
                                }
                            }
                        }
                        $response .= Self::serviceHTML($title, $desc, $config, $layout, $records, $filter, $extraclass);
                    } else if ($module == 'service_template') {
                        $title = $section['val']['title'];
                        $config = $section['val']['config'];
                        $layout = $section['val']['layout'];
                        $filter = $section['val']['template'];
                        if (isset($section['val']['extclass']) && $section['val']['extclass'] != '') {
                            $class = $section['val']['extclass'];
                        } else {
                            $class = '';
                        }
                        $response .= Self::AllservicesHTML($title, $config, $layout, $filter, $class);
                    } else if ($module == 'news') {
                        $title = $section['val']['title'];
                        $config = $section['val']['config'];
                        $desc = $section['val']['desc'];
                        $layout = $section['val']['layout'];
                        $records = $section['val']['records'];
                        $filter = $section['val']['template'];
                        $extraclass = $section['val']['extraclass'];
                        $recIds = array_column($records, 'id');
                        $fields = Self::selectFields($config);

                        $fill = \Powerpanel\News\Models\News::getBuilderNews();

                        if ($fill->count() > 0) {
                            $owlCaurosolLib = 'assets/libraries/owl.carousel/js/owl.carousel.min.js';

                            if (!in_array($owlCaurosolLib, Self::$assets['js']['lib'])) {

                                Self::$assets['js']['lib'][] = $owlCaurosolLib;
                            }

                            $owlCaurosolLib1 = 'assets/libraries/libraries-update/owl.carousel/js/owl.carousel-update.js';

                            if (!in_array($owlCaurosolLib1, Self::$assets['js']['lib'])) {

                                Self::$assets['js']['lib'][] = $owlCaurosolLib1;
                            }

                            $owlCaurosolLib2 = 'assets/js/index.js';

                            if (!in_array($owlCaurosolLib2, Self::$assets['js']['lib'])) {

                                Self::$assets['js']['lib'][] = $owlCaurosolLib2;
                            }

                            if ($module != 'news') {

                                $moduleJS = 'assets/js/news.js';

                                if (!in_array($moduleJS, Self::$assets['js']['modulejs'])) {

                                    Self::$assets['js']['modulejs'][] = $moduleJS;
                                }
                            }
                        }
                        $response .= Self::newsHTML($title, $desc, $config, $layout, $records, $filter, $extraclass);
                    } else if ($module == 'news_template') {
                        $title = $section['val']['title'];
                        $limit = $section['val']['limit'];
                        $desc = $section['val']['desc'];
                        $config = $section['val']['config'];
                        $layout = $section['val']['layout'];
                        $filter = $section['val']['template'];
                        if (isset($section['val']['filter']) && !empty($section['val']['filter'])) {
                            $dbFilter = $section['val']['filter'];
                        } else {
                            $dbFilter = false;
                        }
                        if (isset($section['val']['class']) && $section['val']['class'] != '') {
                            $class = $section['val']['class'];
                        } else {
                            $class = '';
                        }
                        if (isset($section['val']['sdate']) && $section['val']['sdate'] != '') {
                            $sdate = $section['val']['sdate'];
                        } else {
                            $sdate = '';
                        }
                        if (isset($section['val']['edate']) && $section['val']['edate'] != '') {
                            $edate = $section['val']['edate'];
                        } else {
                            $edate = '';
                        }
                        if (isset($section['val']['newscat']) && $section['val']['newscat'] != '') {
                            $newscat = $section['val']['newscat'];
                        } else {
                            $newscat = '';
                        }
                        $response .= Self::AllnewsHTML($title, $limit, $desc, $config, $layout, $filter, $class, $sdate, $edate, $newscat, $dbFilter);
                    } else if ($module == 'publicRecord_template') {
                        $title = $section['val']['title'];
                        $limit = $section['val']['limit'];

                        $filter = $section['val']['template'];
                        if (isset($section['val']['filter']) && !empty($section['val']['filter'])) {
                            $dbFilter = $section['val']['filter'];
                        } else {
                            $dbFilter = false;
                        }
                        if (isset($section['val']['class']) && $section['val']['class'] != '') {
                            $class = $section['val']['class'];
                        } else {
                            $class = '';
                        }

                        if (isset($section['val']['newscat']) && $section['val']['newscat'] != '') {
                            $newscat = $section['val']['newscat'];
                        } else {
                            $newscat = '';
                        }
                        $response .= Self::AllPublicRecordHTML($title, $limit, $filter, $class, $newscat, $dbFilter);
                    } else if ($module == 'candwservice_template') {
                        $title = $section['val']['title'];
                        $limit = $section['val']['limit'];

                        $filter = $section['val']['template'];
                        if (isset($section['val']['filter']) && !empty($section['val']['filter'])) {
                            $dbFilter = $section['val']['filter'];
                        } else {
                            $dbFilter = false;
                        }
                        if (isset($section['val']['class']) && $section['val']['class'] != '') {
                            $class = $section['val']['class'];
                        } else {
                            $class = '';
                        }

                        $response .= Self::AllCandWServiceHTML($title, $limit, $filter, $class, $dbFilter);
                    } else if ($module == 'numberAllocations_template') {

                        $title = $section['val']['title'];
                        $limit = $section['val']['limit'];
                        // $desc = $section['val']['desc'];
                        // $config = $section['val']['config'];
                        // $layout = $section['val']['layout'];
                        $filter = $section['val']['template'];
                        if (isset($section['val']['class']) && $section['val']['class'] != '') {
                            $class = $section['val']['class'];
                        } else {
                            $class = '';
                        }
                        if (isset($section['val']['filter']) && !empty($section['val']['filter'])) {
                            $dbFilter = $section['val']['filter'];
                        } else {
                            $dbFilter = false;
                        }
                        $response .= Self::AllnumberAllocationsHTML($title, $limit, $filter, $class, $dbFilter);
                    } else if ($module == 'registerapplication_template') {
                        $title = $section['val']['title'];
                        $limit = $section['val']['limit'];
                        // $desc = $section['val']['desc'];
                        // $config = $section['val']['config'];
                        // $layout = $section['val']['layout'];
                        $filter = "";
                        if (isset($section['val']['filter']) && !empty($section['val']['filter'])) {
                            $dbFilter = $section['val']['filter'];
                        } else {
                            $dbFilter = false;
                        }

                        $response .= Self::allRegisterApplicationsHTML($title, $limit, $filter, $dbFilter);
                    } else if ($module == 'licenceregister_template') {
                        $title = $section['val']['title'];
                        $limit = $section['val']['limit'];
                        // $desc = $section['val']['desc'];
                        $sector = $section['val']['sector'];
                        // $layout = $section['val']['layout'];
                        $filter = $section['val']['template'];
                        if (isset($section['val']['filter']) && !empty($section['val']['filter'])) {
                            $dbFilter = $section['val']['filter'];
                        } else {
                            $dbFilter = false;
                        }

                        $response .= Self::allLicenseRegisterHTML($title, $limit, $filter, $dbFilter);
                    } else if ($module == 'fmbroadcasting') {
                        $title = $section['val']['title'];
                        $config = $section['val']['config'];
                        $desc = $section['val']['desc'];
                        $layout = $section['val']['layout'];
                        $records = $section['val']['records'];
                        $filter = $section['val']['template'];
                        $extraclass = $section['val']['extraclass'];
                        $recIds = array_column($records, 'id');
                        $fields = Self::selectFields($config);
                        $fill = \Powerpanel\FMBroadcasting\Models\FMBroadcasting::getBuilderFMBroadcasting($recIds);
                        if ($fill->count() > 0) {
                            $owlCaurosolLib = 'assets/libraries/owl.carousel/js/owl.carousel.min.js';

                            if (!in_array($owlCaurosolLib, Self::$assets['js']['lib'])) {

                                Self::$assets['js']['lib'][] = $owlCaurosolLib;
                            }

                            $owlCaurosolLib1 = 'assets/libraries/libraries-update/owl.carousel/js/owl.carousel-update.js';

                            if (!in_array($owlCaurosolLib1, Self::$assets['js']['lib'])) {

                                Self::$assets['js']['lib'][] = $owlCaurosolLib1;
                            }

                            $owlCaurosolLib2 = 'assets/js/index.js';

                            if (!in_array($owlCaurosolLib2, Self::$assets['js']['lib'])) {

                                Self::$assets['js']['lib'][] = $owlCaurosolLib2;
                            }

                            if ($module != 'fmbroadcasting') {

                                $moduleJS = 'assets/js/fmbroadcasting.js';

                                if (!in_array($moduleJS, Self::$assets['js']['modulejs'])) {

                                    Self::$assets['js']['modulejs'][] = $moduleJS;
                                }
                            }
                        }
                        $response .= Self::fmbroadcastingHTML($title, $desc, $config, $layout, $records, $filter, $extraclass);
                    } else if ($module == 'fmbroadcasting_template') {
                        $title = $section['val']['title'];
                        $limit = $section['val']['limit'];
                        $desc = $section['val']['desc'];
                        $config = $section['val']['config'];
                        $layout = $section['val']['layout'];
                        $filter = $section['val']['template'];
                        if (isset($section['val']['filter']) && !empty($section['val']['filter'])) {
                            $dbFilter = $section['val']['filter'];
                        } else {
                            $dbFilter = false;
                        }
                        if (isset($section['val']['class']) && $section['val']['class'] != '') {
                            $class = $section['val']['class'];
                        } else {
                            $class = '';
                        }

                        $response .= Self::AllfmbroadcastingHTML($title, $limit, $desc, $config, $layout, $filter, $class, $dbFilter);
                    } else if ($module == 'consultations_template') {
                        $title = $section['val']['title'];
                        $limit = $section['val']['limit'];
                        $desc = $section['val']['desc'];
                        $config = $section['val']['config'];
                        $layout = $section['val']['layout'];
                        $filter = $section['val']['template'];
                        if (isset($section['val']['filter']) && !empty($section['val']['filter'])) {
                            $dbFilter = $section['val']['filter'];
                        } else {
                            $dbFilter = false;
                        }
                        if (isset($section['val']['class']) && $section['val']['class'] != '') {
                            $class = $section['val']['class'];
                        } else {
                            $class = '';
                        }
                        if (isset($section['val']['sdate']) && $section['val']['sdate'] != '') {
                            $sdate = $section['val']['sdate'];
                        } else {
                            $sdate = '';
                        }
                        if (isset($section['val']['edate']) && $section['val']['edate'] != '') {
                            $edate = $section['val']['edate'];
                        } else {
                            $edate = '';
                        }
                        if (isset($section['val']['blogscat']) && $section['val']['blogscat'] != '') {
                            $blogscat = $section['val']['blogscat'];
                        } else {
                            $blogscat = '';
                        }
                        $response .= Self::AllConsultationsHTML($title, $limit, $desc, $config, $layout, $filter, $class, $sdate, $edate, $blogscat, $dbFilter);
                    } else if ($module == 'latest_news_template') {

                        $title = $section['val']['title'];
                        $filter = $section['val']['template'];

                        if (isset($section['val']['class']) && $section['val']['class'] != '') {
                            $class = $section['val']['class'];
                        } else {
                            $class = '';
                        }

                        $response .= Self::latestNewsHTML($title, $filter, $class);
                    } else if ($module == 'quick_link_template') {

                        $title = $section['val']['title'];
                        $filter = $section['val']['template'];

                        if (isset($section['val']['class']) && $section['val']['class'] != '') {
                            $class = $section['val']['class'];
                        } else {
                            $class = '';
                        }
                        if (isset($section['val']['sdate']) && $section['val']['sdate'] != '') {
                            $sdate = $section['val']['sdate'];
                        } else {
                            $sdate = '';
                        }
                        if (isset($section['val']['edate']) && $section['val']['edate'] != '') {
                            $edate = $section['val']['edate'];
                        } else {
                            $edate = '';
                        }
                        $response .= Self::QuickLinkHTML($title, $filter, $class, $sdate, $edate);
                    } else if ($module == 'links') {
                        $title = $section['val']['title'];
                        $records = $section['val']['records'];
                        $filter = $section['val']['template'];
                        $extraclass = $section['val']['extraclass'];
                        $response .= Self::linksHTML($title, $records, $filter, $extraclass);
                    } else if ($module == 'link_template') {

                        $title = $section['val']['title'];
                        $limit = $section['val']['limit'];
                        $sdate = $section['val']['sdate'];
                        $edate = $section['val']['edate'];
                        $class = $section['val']['class'];
                        $linkcat = $section['val']['linkcat'];

                        $filter = $section['val']['template'];
                        $response .= Self::AllLinksHTML($title, $limit, $sdate, $edate, $class, $linkcat, $filter);
                    } else if ($module == 'faqs') {
                        $title = $section['val']['title'];
                        $records = $section['val']['records'];
                        $filter = $section['val']['template'];
                        $extraclass = $section['val']['extraclass'];
                        $response .= Self::faqsHTML($title, $records, $filter, $extraclass);
                    } else if ($module == 'faq_template') {
                        $title = $section['val']['title'];
                        $limit = isset($section['val']['limit']) ? $section['val']['limit'] : 6;
                        $sdate = isset($section['val']['sdate']) ? $section['val']['sdate'] : '';
                        $edate = isset($section['val']['edate']) ? $section['val']['edate'] : '';
                        $class = isset($section['val']['class']) ? $section['val']['class'] : '';
                        $faqcat = isset($section['val']['faqcat']) ? $section['val']['faqcat'] : '';
                        $filter = $section['val']['template'];
                        $response .= Self::AllFaqsHTML($title, $limit, $sdate, $edate, $class, $faqcat, $filter);
                    } else if ($module == 'publication_template') {

                        $segment1 = Request::segment(1);
                        $segment2 = Request::segment(2);

                        $sector = false;
                        if (($segment1 == "ict" || $segment1 == "water" || $segment1 == "fuel" || $segment1 == "energy") && (!empty($segment2))) {
                            $sector = true;
                            $sector_slug = Request::segment(1);
                        }

                        if (isset($sector_slug)) {
                            $sector_slug = $sector_slug;
                        } else {

                            $sector_slug = 'ofreg';
                        }

                        $title = $section['val']['title'];
                        $limit = $section['val']['limit'];
                        if (isset($section['val']['filter']) && !empty($section['val']['filter'])) {
                            $dbFilter = $section['val']['filter'];
                        } else {
                            $dbFilter = false;
                        }
                        $class = $section['val']['class'];
                        if (isset($section['val']['filter']['category']) && !empty($section['val']['filter']['category'])) {
                            $publicationscat = $section['val']['filter']['category'];
                        } else {
                            $publicationscat = $section['val']['publicationscat'];
                        }
//                            $publicationscat = $section['val']['publicationscat'];
                        $sector = $section['val']['sector'];
                        $filter = $section['val']['template'];
                        $response .= Self::AllpublicationHTML($title, $limit, $sector, $filter, $class, $publicationscat, $dbFilter, $sector_slug);
                    } else if ($module == 'decision_template') {
                        $segment1 = Request::segment(1);
                        $segment2 = Request::segment(2);

                        $sector = false;
                        if (($segment1 == "ict" || $segment1 == "water" || $segment1 == "fuel" || $segment1 == "energy") && (!empty($segment2))) {
                            $sector = true;
                            $sector_slug = Request::segment(1);
                        }

                        if (isset($sector_slug)) {
                            $sector_slug = $sector_slug;
                        } else {

                            $sector_slug = 'ofreg';
                        }
                        $title = $section['val']['title'];
                        $limit = $section['val']['limit'];
                        if (isset($section['val']['filter']) && !empty($section['val']['filter'])) {
                            $dbFilter = $section['val']['filter'];
                        } else {
                            $dbFilter = false;
                        }
                        $class = $section['val']['class'];
                        $decisioncat = $section['val']['decisioncat'];
                        $sector = $section['val']['sector'];
                        $filter = $section['val']['template'];
                        $response .= Self::AlldecisionHTML($title, $limit, $sector, $filter, $class, $decisioncat, $dbFilter, $sector_slug);
                    } else if ($module == 'formsandfees_template') {

                        $title = $section['val']['title'];
                        $limit = $section['val']['limit'];
                        $segment1 = Request::segment(1);
                        $segment2 = Request::segment(2);

                        $sector = false;
                        if (($segment1 == "ict" || $segment1 == "water" || $segment1 == "fuel" || $segment1 == "energy") && (!empty($segment2))) {
                            $sector = true;
                            $sector_slug = Request::segment(1);
                        }

                        if (isset($sector_slug)) {
                            $sector_slug = $sector_slug;
                        } else {

                            $sector_slug = 'ofreg';
                        }
                        if (isset($section['val']['filter']) && !empty($section['val']['filter'])) {
                            $dbFilter = $section['val']['filter'];
                        } else {
                            $dbFilter = false;
                        }
                        $class = $section['val']['class'];

                        $filter = $section['val']['template'];
                        $response .= Self::AllformsandfeesHTML($title, $limit, $filter, $class, $dbFilter, $sector_slug);
                    } else if ($module == 'home-img_content') {
                        $title = $section['val']['title'];
                        $image = $section['val']['image'];
                        $content = $section['val']['content'];
                        $alignment = $section['val']['alignment'];
                        $src = $section['val']['src'];
                        $response .= Self::WelComeHTML($title, $image, $content, $alignment, $src);
                    } else if ($module == 'map') {
                        $latitude = $section['val']['latitude'];
                        $longitude = $section['val']['longitude'];
                        $response .= Self::MapHTML($latitude, $longitude);
                    } else if ($module == 'conatct_info') {
                        $content = $section['val']['content'];
                        $section_address = $section['val']['section_address'];
                        $section_email = $section['val']['section_email'];
                        $section_phone = $section['val']['section_phone'];
                        $response .= Self::ConatctInfoHTML($content, $section_address, $section_email, $section_phone);
                    } else if ($module == 'button_info') {
                        $title = $section['val']['title'];
                        $content = $section['val']['content'];
                        $alignment = $section['val']['alignment'];
                        $target = $section['val']['target'];
                        $response .= Self::ButtonHTML($title, $content, $alignment, $target);
                    } else if ($module == 'custom_section') {

                        $title = $section['val']['title'];

                        $layout = $section['val']['layout'];
                        $records = $section['val']['records'];
                        $class = $section['val']['extclass'];

                        $response .= Self::CustomSectionHTML($title, $layout, $records, $class);
                    }

                    $i++;
                }
            }
        }
        return ['response' => $response, 'assets' => Self::$assets];
    }

    public static function eventHTML($title, $desc, $config, $layout, $records, $filter, $extraclass, $today)
    {
        $response = '';
        $today = date('d-m-y H:i:s');
        $recIds = array_column($records, 'id');
        $fields = Self::selectFields($config);
        if (File::exists(base_path() . '/packages/Powerpanel/Events/src/Models/Events.php') != null) {
            $fill = \Powerpanel\Events\Models\Events::getBuilderEvents($fields, $recIds);
            foreach ($records as $key => $row) {
                if (count(array_unique($row['custom_fields'])) > 1) {
                    $fill[($key - 1)]->custom = $row['custom_fields'];
                }
            }
            if (!empty($fill)) {
                $data = [
                    'title' => $title,
                    'desc' => $desc,
                    'events' => $fill,
                    'paginatehrml' => false,
                    'cols' => trim($layout),
                    'filter' => $filter,
                    'class' => $extraclass,
                    'today' => $today,
                ];
                $response = view('events::frontview.builder-sections.events', compact('data'))->render();
            }
        } else {
            $response = '';
        }
        return $response;
    }

    public static function AlleventsHTML($title, $limit, $desc, $config, $layout, $filter, $class, $sdate, $edate, $eventscat, $today, $dbFilter = false)
    {
        $response = '';
        $today = date('d-m-y H:i:s');
        //        $recIds = array_column($records, 'id');
        $fields = Self::selectFields($config);
        if (File::exists(base_path() . '/packages/Powerpanel/Events/src/Models/Events.php') != null) {
            if ($filter == 'current-months-events') {
                $fill = \Powerpanel\Events\Models\Events::getCurrentMonthEvents($fields, $limit, $sdate, $edate, $eventscat, $today, $dbFilter);
            } else {
                $fill = \Powerpanel\Events\Models\Events::getAllEvents($fields, $limit, $sdate, $edate, $eventscat, $today, $dbFilter);
            }
            if (!empty($fill)) {
                $eventRecord = array();
                $today = date('Y-m-d');
                $tomorrow = date('Y-m-d', strtotime($today . ' + 1 day'));
                $saturday = $start_date = date("Y-m-d", strtotime('Saturday'));
                $sunday = $start_date = date("Y-m-d", strtotime('Sunday'));
                foreach ($fill as $key => $event) {
                    $status = false;
                    $eventData = json_decode($event->dtDateTime);
                    foreach ($eventData as $eventKey => $value) {
                        $eventData[$eventKey]->attendeeRegistered = array();
                        foreach ($value->timeSlotFrom as $timeKey => $timeSlot) {
                            $eventLeadCount = \Powerpanel\Events\Models\EventLead::getEventAttendeeCount($event->id, $value->startDate, $value->endDate, $timeSlot, $value->timeSlotTo[$timeKey]);
                            array_push($eventData[$eventKey]->attendeeRegistered, $eventLeadCount);
                        }

                        $event->dtDateTime = json_encode($eventData);

                        if (isset($dbFilter['dateFilter']) && !empty($dbFilter['dateFilter'])) {
                            if ($dbFilter['dateFilter'] == 'today') {
                                if ($value->startDate <= $today && $today <= $value->endDate) {
                                    $status = true;
                                    goto OuterLoop;
                                }

                            } elseif ($dbFilter['dateFilter'] == 'tomorrow') {
                                if ($value->startDate <= $tomorrow && $tomorrow <= $value->endDate) {
                                    $status = true;
                                    goto OuterLoop;
                                }

                            } elseif ($dbFilter['dateFilter'] == 'weekend') {
                                if (($saturday <= $value->startDate && $value->startDate <= $sunday) || ($saturday <= $value->endDate && $value->endDate <= $sunday)) {
                                    $status = true;
                                    goto OuterLoop;
                                }
                            }
                        } else {
                            if ($value->startDate >= $today && $today <= $value->endDate) {
                                $status = true;
                                goto OuterLoop;
                            }
                        }
                    }
                    OuterLoop:
                    if (isset($dbFilter['dateFilter']) && !empty($dbFilter['dateFilter'])) {
                        if ($status) {
                            $event->isRSVP = 'Y';
                            array_push($eventRecord, $event);
                        }

                    } else {
                        if ($status) {
                            $event->isRSVP = 'Y';
                        } else {
                            $event->isRSVP = 'N';
                        }
                        array_push($eventRecord, $event);
                    }
                    continue;
                }
                $data = [
                    'title' => $title,
                    'limit' => $limit,
                    'desc' => $desc,
                    'events' => $eventRecord,
                    'paginatehrml' => true,
                    'cols' => trim($layout),
                    'filter' => $filter,
                    'class' => $class,
                    'today' => $today,
                ];
                $response = view('events::frontview.builder-sections.events', compact('data'))->render();
            }
        } else {
            $response = '';
        }
        return $response;
    }

    public static function AllnumberAllocationsHTML($title, $limit, $filter, $class, $dbFilter = false)
    {
        $response = '';

        $fields = false; //Self::selectFields($config);
        if (File::exists(base_path() . '/packages/Powerpanel/NumberAllocation/src/Models/NumberAllocation.php') != null) {
            $fill = \Powerpanel\NumberAllocation\Models\NumberAllocation::getAllNumberAllocations($limit, $dbFilter);
            if (!empty($fill)) {
                $data = [
                    'title' => $title,
                    'limit' => $limit,
                    'numberAllocations' => $fill,
                    'paginatehrml' => false,
                    'class' => $class,
                ];
                $response = view('number-allocation::frontview.builder-sections.all-number-allocation', compact('data'))->render();
            }
        } else {
            $response = '';
        }
        return $response;
    }

    public static function newsHTML($title, $desc, $config, $layout, $records, $filter, $extraclass)
    {
        $response = '';
        $recIds = array_column($records, 'id');
        $fields = Self::selectFields($config);
        if (File::exists(base_path() . '/packages/Powerpanel/News/src/Models/News.php') != null) {
            $fill = \Powerpanel\News\Models\News::getBuilderNews();

            foreach ($records as $key => $row) {
                if (count(array_unique($row['custom_fields'])) > 1) {
                    $fill[($key - 1)]->custom = $row['custom_fields'];
                }
            }
            if (!empty($fill)) {
                $data = [
                    'title' => $title,
                    'desc' => $desc,
                    'news' => $fill,
                    'paginatehrml' => false,
                    'cols' => trim($layout),
                    'filter' => $filter,
                    'class' => $extraclass,
                ];
                $response = view('news::frontview.builder-sections.news', compact('data'))->render();
            }
        } else {
            $response = '';
        }
        return $response;
    }

    public static function AllnewsHTML($title, $limit, $desc, $config, $layout, $filter, $class, $sdate, $edate, $newscat, $dbFilter = false)
    {
        $response = '';

        $fields = Self::selectFields($config);
        if (File::exists(base_path() . '/packages/Powerpanel/News/src/Models/News.php') != null) {
            $fill = \Powerpanel\News\Models\News::getAllNews($fields, $limit, $sdate, $edate, $newscat, $dbFilter);
            if (!empty($fill)) {
                $data = [
                    'title' => $title,
                    'limit' => $limit,
                    'desc' => $desc,
                    'news' => $fill,
                    'paginatehrml' => true,
                    'cols' => trim($layout),
                    'filter' => $filter,
                    'class' => $class,
                ];
                $response = view('news::frontview.builder-sections.all-news', compact('data'))->render();
            }
        } else {
            $response = '';
        }
        return $response;
    }

    public static function AllPublicRecordHTML($title, $limit, $filter, $class, $newscat, $dbFilter = false)
    {
        $response = '';

        if (File::exists(base_path() . '/packages/Powerpanel/PublicRecord/src/Models/PublicRecord.php') != null) {
            $fill = \Powerpanel\PublicRecord\Models\PublicRecord::getAllPublicRecord($limit, $newscat, $dbFilter);
            if (!empty($fill)) {
                $data = [
                    'title' => $title,
                    'limit' => $limit,

                    'publicRecords' => $fill,
                    'paginatehrml' => true,

                    'filter' => $filter,
                    'class' => $class,
                ];
                $response = view('public-record::frontview.builder-sections.all-public-record', compact('data'))->render();
            }
        } else {
            $response = '';
        }
        return $response;
    }

    public static function AllCandWServiceHTML($title, $limit, $filter, $class, $dbFilter = false)
    {
        $response = '';

//        $fields = Self::selectFields($config);
        if (File::exists(base_path() . '/packages/Powerpanel/CandWService/src/Models/CandWService.php') != null) {
            $fill = \Powerpanel\CandWService\Models\CandWService::getAllCandWServices($limit, $dbFilter);
            if (!empty($fill)) {
                $data = [
                    'title' => $title,
                    'limit' => $limit,

                    'candwServices' => $fill,
                    'paginatehrml' => true,

                    'filter' => $filter,
                    'class' => $class,
                ];
                $response = view('candwservice::frontview.builder-sections.all-candwservice', compact('data'))->render();
            }
        } else {
            $response = '';
        }
        return $response;
    }

    public static function fmbroadcastingHTML($title, $desc, $config, $layout, $records, $filter, $extraclass)
    {
        $response = '';
        $recIds = array_column($records, 'id');
        $fields = Self::selectFields($config);
        if (File::exists(base_path() . '/packages/Powerpanel/FMBroadcasting/src/Models/FMBroadcasting.php') != null) {
            $fill = \Powerpanel\FMBroadcasting\Models\FMBroadcasting::getBuilderFMBroadcasting($recIds);

            foreach ($records as $key => $row) {
                if (count(array_unique($row['custom_fields'])) > 1) {
                    $fill[($key - 1)]->custom = $row['custom_fields'];
                }
            }
            if (!empty($fill)) {
                $data = [
                    'title' => $title,
                    'desc' => $desc,
                    'fmbroadcasting' => $fill,
                    'paginatehrml' => false,
                    'cols' => trim($layout),
                    'filter' => $filter,
                    'class' => $extraclass,
                ];

                $response = view('fmbroadcasting::frontview.builder-sections.fmbroadcasting', compact('data'))->render();
            }
        } else {
            $response = '';
        }
        return $response;
    }

    public static function AllfmbroadcastingHTML($title, $limit, $desc, $config, $layout, $filter, $class, $dbFilter = false)
    {
        $response = '';

        $fields = Self::selectFields($config);
        if (File::exists(base_path() . '/packages/Powerpanel/FMBroadcasting/src/Models/FMBroadcasting.php') != null) {
            $fill = \Powerpanel\FMBroadcasting\Models\FMBroadcasting::getAllFMBroadcasting($fields, $limit, $dbFilter);
            if (!empty($fill)) {
                $data = [
                    'title' => $title,
                    'limit' => $limit,
                    'desc' => $desc,
                    'fmbroadcasting' => $fill,
                    'paginatehrml' => true,
                    'cols' => trim($layout),
                    'filter' => $filter,
                    'class' => $class,
                ];

                $response = view('fmbroadcasting::frontview.builder-sections.fmbroadcasting', compact('data'))->render();
            }
        } else {
            $response = '';
        }
        return $response;
    }

    public static function AllConsultationsHTML($title, $limit, $desc, $config, $layout, $filter, $class, $sdate, $edate, $blogscat, $dbFilter = false)
    {
        $response = '';

        $fields = Self::selectFields($config);
        if (File::exists(base_path() . '/packages/Powerpanel/Consultations/src/Models/Consultations.php') != null) {
            $fill = \Powerpanel\Consultations\Models\Consultations::getAllConsultations($fields, $limit, $sdate, $edate, $blogscat, $dbFilter);
            if (!empty($fill)) {
                $data = [
                    'title' => $title,
                    'limit' => $limit,
                    'desc' => $desc,
                    'consultations' => $fill,
                    'paginatehrml' => true,
                    'cols' => trim($layout),
                    'filter' => $filter,
                    'class' => $class,
                ];
                $response = view('consultations::frontview.builder-sections.all-consultations', compact('data'))->render();
            }
        } else {
            $response = '';
        }
        return $response;
    }

    public static function allRegisterApplicationsHTML($title, $limit, $filter, $dbFilter = false)
    {
        $response = '';

        $fields = false; //Self::selectFields($config);
        if (File::exists(base_path() . '/packages/Powerpanel/RegisterApplication/src/Models/RegisterApplication.php') != null) {
            $fill = \Powerpanel\RegisterApplication\Models\RegisterApplication::getAllRegisterApplications($fields, $limit, $dbFilter);
            if (!empty($fill)) {
                $data = [
                    'title' => $title,
                    'limit' => $limit,
                    'registerApplications' => $fill,
                    'paginatehrml' => true,
                    'filter' => $filter,
                ];
                $response = view('register-application::frontview.builder-sections.all-register-application', compact('data'))->render();
            }
        } else {
            $response = '';
        }
        return $response;
    }

    public static function allLicenseRegisterHTML($title, $limit, $filter, $dbFilter = false)
    {
        $response = '';

        $fields = false;
        if (File::exists(base_path() . '/packages/Powerpanel/LicenceRegister/src/Models/LicenceRegister.php') != null) {
            $fill = \Powerpanel\LicenceRegister\Models\LicenceRegister::getAllLicenseRegister($fields, $limit, $dbFilter);
            if (!empty($fill)) {
                $data = [
                    'title' => $title,
                    'limit' => $limit,

                    'licenseRegisters' => $fill,
                    'paginatehrml' => true,

                    'filter' => $filter,
                ];
                $response = view('licence-register::frontview.builder-sections.all-licence-register', compact('data'))->render();
            }
        } else {
            $response = '';
        }
        return $response;
    }

    public static function interconnectionsHTML($title, $parentorg, $orgclass, $filter, $dbFilter = false)
    {
        $response = '';

        $data = [
            'title' => $title,
            'class' => $orgclass,
            'filter' => $filter,
            'cols' => 'grid_2_col',
            'paginatehrml' => true,
        ];
        if (File::exists(base_path() . '/packages/Powerpanel/Interconnections/src/Models/Interconnections.php') != null) {
            $interconnectionData = \Powerpanel\Interconnections\Models\Interconnections::getBuilderInterconnections($parentorg, $dbFilter);
            $interdata = array();
            if (!empty($interconnectionData) && count($interconnectionData) > 0) {
                foreach ($interconnectionData as $interconnection) {

                    $ogData = array();
                    $tempData = array();
                    $tempData['v'] = (String) $interconnection['id'];
                    $tempData['f'] = $interconnection['varTitle'];
                    $ogData[] = $tempData;
                    if ($interconnection['intParentCategoryId'] > 0) {
                        array_push($ogData, (String) $interconnection['intParentCategoryId']);
                    } else {
                        array_push($ogData, null);
                    }
                    array_push($ogData, addslashes($interconnection['varTitle']));
                    $interdata[] = $ogData;
                }
            }
            $interdata = json_encode($interdata);
            // $data['interdata'] = $interdata;
            $data['interconnections'] = $interconnectionData;
            $data['class'] = $orgclass;
            // , 'interdata', 'orgclass'
            $response = view('interconnections::frontview.builder-sections.interconnections', compact('data'))->render();
        } else {
            $response = '';
        }
        return $response;
    }

    public static function latestNewsHTML($title, $filter, $class)
    {
        $response = '';
        if (File::exists(base_path() . '/packages/Powerpanel/News/src/Models/News.php') != null) {
            $fill = \Powerpanel\News\Models\News::getLatestNews();

            if (!empty($fill)) {
                $data = [
                    'title' => $title,
                    'latestNews' => $fill,
                    'filter' => $filter,
                    'class' => $class,
                ];
                $response = view('news::frontview.builder-sections.latest-news', compact('data'))->render();
            }
        } else {
            $response = '';
        }
        return $response;
    }
    public static function QuickLinkHTML($title, $filter, $class, $sdate, $edate)
    {
        $response = '';
        if (File::exists(base_path() . '/packages/Powerpanel/QuickLinks/src/Models/QuickLinks.php') != null) {
            $fill = \Powerpanel\QuickLinks\Models\QuickLinks::getQuickLinks($sdate, $edate);

            if (!empty($fill)) {
                $data = [
                    'title' => $title,
                    'quickLink' => $fill,
                    'filter' => $filter,
                    'class' => $class,
                ];
                $response = view('quick-links::frontview.builder-sections.quick-links', compact('data'))->render();
            }
        } else {
            $response = '';
        }
        return $response;
    }

    public static function alertsHTML($title, $records, $filter, $extraclass)
    {
        $response = '';
        $recIds = array_column($records, 'id');
        if (File::exists(base_path() . '/packages/Powerpanel/Alerts/src/Models/Alerts.php') != null) {
            $fill = \Powerpanel\Alerts\Models\Alerts::getBuilderAlerts($recIds);
            foreach ($records as $key => $row) {
                if (count(array_unique($row['custom_fields'])) > 1) {
                    $fill[($key - 1)]->custom = $row['custom_fields'];
                }
            }
            if (!empty($fill)) {
                $data = [
                    'title' => $title,
                    'alerts' => $fill,
                    'paginatehrml' => false,
                    'filter' => $filter,
                    'class' => $extraclass,
                ];
                $alertsArr = \Powerpanel\Alerts\Models\Alerts::getBuilderAlerts($recIds);
                if (!empty($alertsArr)) {
                    foreach ($alertsArr as $key => $value) {
                        $linkUrl = \Powerpanel\Alerts\Models\Alerts::getInternalLinkHtml($value);
                        $data[$key]['url'] = $linkUrl;
                        $data[$key]['moduleName'] = $value->modules->varModuleName;
                        $data[$key]['moduleId'] = $value->modules->id;
                        $data[$key]['varTitle'] = $value->varTitle;
                        $data[$key]['intAlertType'] = $value->intAlertType;
                    }
                }
                $resultarry = array();
                foreach ($data as $row) {
                    if (isset($row['intAlertType'])) {
                        $resultarry[$row['intAlertType']][] = $row;
                    }
                }
                $data['alertsArr'] = $resultarry;
                $response = view('alerts::frontview.builder-sections.alerts', compact('data'))->render();
            }
        } else {
            $response = '';
        }
        return $response;
    }

    public static function AllalertsHTML($title, $limit, $alerttype, $sdate, $edate, $class, $filter)
    {
        $response = '';
        if (File::exists(base_path() . '/packages/Powerpanel/Alerts/src/Models/Alerts.php') != null) {
            $fill = \Powerpanel\Alerts\Models\Alerts::getAllAlerts($limit, $alerttype, $sdate, $edate);
            if (!empty($fill)) {
                $data = [
                    'title' => $title,
                    'limit' => $limit,
                    'alerttype' => $alerttype,
                    'alerts' => $fill,
                    'paginatehrml' => true,
                    'class' => $class,
                    'filter' => $filter,
                ];
                $alertsArr = \Powerpanel\Alerts\Models\Alerts::getAllAlerts($limit, $alerttype, $sdate, $edate);

                if (!empty($alertsArr)) {
                    foreach ($alertsArr as $key => $value) {
                        $linkUrl = \Powerpanel\Alerts\Models\Alerts::getInternalLinkHtml($value);
                        $data[$key]['url'] = $linkUrl;
                        $data[$key]['moduleName'] = $value->modules->varModuleName;
                        $data[$key]['moduleId'] = $value->modules->id;
                        $data[$key]['varTitle'] = $value->varTitle;
                        $data[$key]['intAlertType'] = $value->intAlertType;
                    }
                }
                $resultarry = array();
                foreach ($data as $row) {
                    if (isset($row['intAlertType'])) {
                        $resultarry[$row['intAlertType']][] = $row;
                    }
                }

                $data['alertsArr'] = $resultarry;
                $response = view('alerts::frontview.builder-sections.alerts', compact('data'))->render();
            }
        } else {
            $response = '';
        }
        return $response;
    }

    public static function galleryHTML($title, $config, $layout, $records, $filter, $innerpage = null, $extclass = null)
    {
        $response = '';
        $data = array();
        $recIds = array_column($records, 'id');
        $fields = Self::selectFields($config);
        $limit = 12;
        $galleryObj = \Powerpanel\Gallery\Models\Gallery::getGalleryList($recIds, $limit);
        foreach ($records as $key => $row) {
            if (count(array_unique($row['custom_fields'])) > 1) {
                $galleryObj[($key - 1)]->custom = $row['custom_fields'];
            }
        }
        if ($galleryObj->count() > 0) {
            $listingPage = false;
            if (Request::segment(1) != null && Request::segment(2) == null) {
                $listingPage = true;
            }
            $detailPage = false;
            if (Request::segment(1) != null && Request::segment(2) != null) {
                $detailPage = true;
            }
            $data['listingPage'] = $listingPage;
            $data['detailPage'] = $detailPage;
            $data['title'] = $title;
            $data['cols'] = trim($layout);
            $data['innerpage'] = $innerpage;
            $data['extclass'] = trim($extclass);
            $data['filter'] = $filter;
            $data['imageGalleyObj'] = $galleryObj;
            $response = view('gallery::frontview.builder-sections.gallery-list', $data)->render();
        }
        return $response;
    }

    public static function AllgalleryHTML($title, $class, $config, $layout, $filter)
    {
        $response = '';
        $data = array();
        $fields = Self::selectFields($config);
        $limit = 12;
        $galleryObj = \Powerpanel\Gallery\Models\Gallery::getTemplateGalleryList($filter);

        if ($galleryObj->count() > 0) {
            $listingPage = false;
            if (Request::segment(1) != null && Request::segment(2) == null) {
                $listingPage = true;
            }
            $detailPage = false;
            if (Request::segment(1) != null && Request::segment(2) != null) {
                $detailPage = true;
            }
            $data['title'] = $title;
            $data['cols'] = trim($layout);
            $data['extclass'] = $class;
            $data['imageGalleyObj'] = $galleryObj;
            $data['listingPage'] = $listingPage;
            $data['detailPage'] = $detailPage;
            $response = view('gallery::frontview.builder-sections.gallery-list', $data)->render();
        }
        return $response;
    }

    public static function showHTML($title, $records, $filter, $config, $layout)
    {
        $response = '';
        $recIds = array_column($records, 'id');
        if (File::exists(base_path() . '/packages/Powerpanel/Show/src/Models/Show.php') != null) {
            $fill = \Powerpanel\Show\Models\Show::getBuilderShows($recIds);
            foreach ($records as $key => $row) {
                if (count(array_unique($row['custom_fields'])) > 1) {
                    $fill[($key - 1)]->custom = $row['custom_fields'];
                }
            }
            if (!empty($fill)) {
                $data = [
                    'title' => $title,
                    'show' => $fill,
                    'filter' => $filter,
                    'config' => $config,
                    'paginatehrml' => false,
                    'cols' => $layout,
                ];
                $response = view('show::frontview.builder-sections.shows', compact('data'))->render();
            }
        } else {
            $response = '';
        }
        return $response;
    }

    public static function AllshowHTML($title, $class, $config, $layout, $filter)
    {
        $response = '';
        if (File::exists(base_path() . '/packages/Powerpanel/Show/src/Models/Show.php') != null) {
            $fill = \Powerpanel\Show\Models\Show::getTemplateShowList();
            if (!empty($fill)) {
                $data = [
                    'title' => $title,
                    'show' => $fill,
                    'class' => $class,
                    'filter' => $filter,
                    'config' => $config,
                    'paginatehrml' => true,
                    'cols' => $layout,
                ];
                $response = view('show::frontview.builder-sections.shows', compact('data'))->render();
            }
        } else {
            $response = '';
        }
        return $response;
    }

    public static function productHTML($title, $records, $filter, $class, $config, $layout)
    {
        $response = '';
        $recIds = array_column($records, 'id');
        if (File::exists(base_path() . '/packages/Powerpanel/Products/src/Models/Products.php') != null) {
            $fill = \Powerpanel\Products\Models\Products::getProductList($recIds);
            foreach ($records as $key => $row) {
                if (count(array_unique($row['custom_fields'])) > 1) {
                    $fill[($key - 1)]->custom = $row['custom_fields'];
                }
            }
            if (!empty($fill)) {
                $data = [
                    'title' => $title,
                    'products' => $fill,
                    'class' => $class,
                    'filter' => $filter,
                    'config' => $config,
                    'paginatehrml' => false,
                    'cols' => $layout,
                ];
                $response = view('products::frontview.builder-sections.products', compact('data'))->render();
            }
        } else {
            $response = '';
        }
        return $response;
    }

    public static function AllproductHTML($title, $class, $config, $layout, $filter)
    {
        $response = '';
        if (File::exists(base_path() . '/packages/Powerpanel/Products/src/Models/Products.php') != null) {
            $fill = \Powerpanel\Products\Models\Products::getTemplateProductList();
            if (!empty($fill)) {
                $data = [
                    'title' => $title,
                    'products' => $fill,
                    'class' => $class,
                    'filter' => $filter,
                    'config' => $config,
                    'paginatehrml' => true,
                    'cols' => $layout,
                ];
                $response = view('products::frontview.builder-sections.products', compact('data'))->render();
            }
        } else {
            $response = '';
        }
        return $response;
    }

    public static function testimonialHTML($title, $records, $filter, $layout)
    {
        $response = '';
        $response = '';
        $recIds = array_column($records, 'id');
        if (File::exists(base_path() . '/packages/Powerpanel/Testimonial/src/Models/Testimonial.php') != null) {
            $fill = \Powerpanel\Testimonial\Models\Testimonial::getTestimonialList($recIds);
            foreach ($records as $key => $row) {
                if (count(array_unique($row['custom_fields'])) > 1) {
                    $fill[($key - 1)]->custom = $row['custom_fields'];
                }
            }
            if (!empty($fill)) {
                $data = [
                    'title' => $title,
                    'testimonial' => $fill,
                    'paginatehrml' => false,
                    'filter' => $filter,
                    'cols' => $layout,
                ];

                $response = view('testimonial::frontview.builder-sections.testimonial', compact('data'))->render();
            }
        } else {
            $response = '';
        }
        return $response;
    }

    public static function AlltestimonialHTML($title, $config, $layout, $class, $filter)
    {
        $response = '';
        if (File::exists(base_path() . '/packages/Powerpanel/Testimonial/src/Models/Testimonial.php') != null) {
            $fill = \Powerpanel\Testimonial\Models\Testimonial::getTemplateTestimonialList();
            if (!empty($fill)) {
                $data = [
                    'title' => $title,
                    'config' => $config,
                    'testimonial' => $fill,
                    'class' => $class,
                    'paginatehrml' => true,
                    'cols' => $layout,
                    'filter' => $filter,
                ];
                $response = view('testimonial::frontview.builder-sections.testimonial', compact('data'))->render();
            }
        } else {
            $response = '';
        }
        return $response;
    }

    public static function teamHTML($title, $desc, $class, $config, $records, $filter, $layout)
    {
        $response = '';
        $response = '';
        $recIds = array_column($records, 'id');
        if (File::exists(base_path() . '/packages/Powerpanel/Team/src/Models/Team.php') != null) {
            $fill = \Powerpanel\Team\Models\Team::getBuilderTeam($recIds);
            foreach ($records as $key => $row) {
                if (count(array_unique($row['custom_fields'])) > 1) {
                    $fill[($key - 1)]->custom = $row['custom_fields'];
                }
            }
            if (!empty($fill)) {
                $data = [
                    'title' => $title,
                    'desc' => $desc,
                    'class' => $class,
                    'config' => $config,
                    'team' => $fill,
                    'paginatehrml' => false,
                    'filter' => $filter,
                    'cols' => $layout,
                ];

                $response = view('team::frontview.builder-sections.team', compact('data'))->render();
            }
        } else {
            $response = '';
        }
        return $response;
    }

    public static function AllteamHTML($title, $config, $layout, $class, $filter, $dbFilter)
    {
        $response = '';
        if (File::exists(base_path() . '/packages/Powerpanel/Team/src/Models/Team.php') != null) {

            $fill = \Powerpanel\Team\Models\Team::getTemplateTeamList($dbFilter);
            if (!empty($fill)) {
                $data = [
                    'title' => $title,
                    'config' => $config,
                    'team' => $fill,
                    'class' => $class,
                    'paginatehrml' => true,
                    'cols' => $layout,
                    'filter' => $filter,
                ];
                $response = view('team::frontview.builder-sections.team', compact('data'))->render();
            }
        } else {
            $response = '';
        }
        return $response;
    }
    public static function boardofdirectorsHTML($title, $desc, $class, $config, $records, $filter, $layout)
    {
        $response = '';
        $response = '';
        $recIds = array_column($records, 'id');
        if (File::exists(base_path() . '/packages/Powerpanel/BoardOfDirectors/src/Models/BoardOfDirectors.php') != null) {
            $fill = \Powerpanel\BoardOfDirectors\Models\BoardOfDirectors::getBuilderBoard($recIds);
            foreach ($records as $key => $row) {
                if (count(array_unique($row['custom_fields'])) > 1) {
                    $fill[($key - 1)]->custom = $row['custom_fields'];
                }
            }
            if (!empty($fill)) {
                $data = [
                    'title' => $title,
                    'desc' => $desc,
                    'class' => $class,
                    'config' => $config,
                    'boardofdirectors' => $fill,
                    'paginatehrml' => false,
                    'filter' => $filter,
                    'cols' => $layout,
                ];

                $response = view('boardofdirectors::frontview.builder-sections.boardofdirectors', compact('data'))->render();
            }
        } else {
            $response = '';
        }
        return $response;
    }

    public static function allBoardOfDirectorsHTML($title, $config, $layout, $class, $filter, $dbFilter)
    {
        $response = '';
        if (File::exists(base_path() . '/packages/Powerpanel/BoardOfDirectors/src/Models/BoardOfDirectors.php')) {
            $fill = \Powerpanel\BoardOfDirectors\Models\BoardOfDirectors::getTemplateTeamList($dbFilter);
            if (!empty($fill)) {
                $data = [
                    'title' => $title,
                    'config' => $config,
                    'boardofdirectors' => $fill,
                    'class' => $class,
                    'paginatehrml' => true,
                    'cols' => $layout,
                    'filter' => $filter,
                ];

                $response = view('boardofdirectors::frontview.builder-sections.boardofdirectors', compact('data'))->render();
            }
        } else {
            $response = '';
        }
        return $response;
    }

    public static function publicationHTML($title, $desc, $class, $config, $records, $filter, $layout)
    {
        $response = '';
        $response = '';
        $recIds = array_column($records, 'id');
        if (File::exists(base_path() . '/packages/Powerpanel/Publications/src/Models/Publications.php') != null) {
            $fill = \Powerpanel\Publications\Models\Publications::getBuilderPublication($recIds);
            foreach ($records as $key => $row) {
                if (count(array_unique($row['custom_fields'])) > 1) {
                    $fill[($key - 1)]->custom = $row['custom_fields'];
                }
            }
            if (!empty($fill)) {
                $data = [
                    'title' => $title,
                    'desc' => $desc,
                    'class' => $class,
                    'config' => $config,
                    'publication' => $fill,
                    'paginatehrml' => false,
                    'filter' => $filter,
                    'cols' => $layout,
                ];

                $response = view('publications::frontview.builder-sections.publication', compact('data'))->render();
            }
        } else {
            $response = '';
        }
        return $response;
    }
    public static function complaintservicesHTML($title, $desc, $class, $config, $records, $filter, $layout)
    {
        $response = '';
        $response = '';
        $recIds = array_column($records, 'id');
        if (File::exists(base_path() . '/packages/Powerpanel/ComplaintServices/src/Models/ComplaintServices.php') != null) {
            $fill = \Powerpanel\ComplaintServices\Models\ComplaintServices::getBuilderComplaintServices($recIds);
            foreach ($records as $key => $row) {
                if (count(array_unique($row['custom_fields'])) > 1) {
                    $fill[($key - 1)]->custom = $row['custom_fields'];
                }
            }
            if (!empty($fill)) {
                $data = [
                    'title' => $title,
                    'desc' => $desc,
                    'class' => $class,
                    'config' => $config,
                    'complaintservices' => $fill,
                    'paginatehrml' => false,
                    'filter' => $filter,
                    'cols' => $layout,
                ];

                $response = view('complaint-services::frontview.builder-sections.complaint-services', compact('data'))->render();
            }
        } else {
            $response = '';
        }
        return $response;
    }
    public static function allComplaintServicesHTML($title, $config, $layout, $class, $desc, $filter, $dbFilter)
    {
        $response = '';
        if (File::exists(base_path() . '/packages/Powerpanel/ComplaintServices/src/Models/ComplaintServices.php')) {
            $fill = \Powerpanel\ComplaintServices\Models\ComplaintServices::getTemplateComplaintServicesList($dbFilter);

            if (!empty($fill)) {
                $data = [
                    'title' => $title,
                    'config' => $config,
                    'complaintservices' => $fill,
                    'class' => $class,
                    'paginatehrml' => true,
                    'cols' => $layout,
                    'filter' => $filter,
                ];

                $response = view('complaint-services::frontview.builder-sections.complaint-services', compact('data'))->render();
            }
        } else {
            $response = '';
        }
        return $response;
    }

    public static function clientHTML($title, $desc, $class, $config, $records, $filter, $layout)
    {
        $response = '';
        $response = '';
        $recIds = array_column($records, 'id');
        if (File::exists(base_path() . '/packages/Powerpanel/Client/src/Models/Client.php') != null) {
            $fill = \Powerpanel\Client\Models\Client::getClientList($recIds);
            foreach ($records as $key => $row) {
                if (count(array_unique($row['custom_fields'])) > 1) {
                    $fill[($key - 1)]->custom = $row['custom_fields'];
                }
            }
            if (!empty($fill)) {
                $data = [
                    'title' => $title,
                    'desc' => $desc,
                    'class' => $class,
                    'config' => $config,
                    'client' => $fill,
                    'paginatehrml' => false,
                    'filter' => $filter,
                    'cols' => $layout,
                ];

                $response = view('client::frontview.builder-sections.clients', compact('data'))->render();
            }
        } else {
            $response = '';
        }
        return $response;
    }

    public static function AllclientHTML($title, $config, $layout, $class, $filter)
    {
        $response = '';
        if (File::exists(base_path() . '/packages/Powerpanel/Client/src/Models/Client.php') != null) {
            $fill = \Powerpanel\Client\Models\Client::getTemplateClientList();
            if (!empty($fill)) {
                $data = [
                    'title' => $title,
                    'config' => $config,
                    'client' => $fill,
                    'class' => $class,
                    'paginatehrml' => true,
                    'cols' => $layout,
                    'filter' => $filter,
                ];
                $response = view('client::frontview.builder-sections.clients', compact('data'))->render();
            }
        } else {
            $response = '';
        }
        return $response;
    }

    public static function projectHTML($title, $desc, $class, $config, $records, $filter, $layout)
    {
        $response = '';
        $response = '';
        $recIds = array_column($records, 'id');
        if (File::exists(base_path() . '/packages/Powerpanel/Projects/src/Models/Projects.php') != null) {
            $fill = \Powerpanel\Projects\Models\Projects::getProjectsList($recIds);
            foreach ($records as $key => $row) {
                if (count(array_unique($row['custom_fields'])) > 1) {
                    $fill[($key - 1)]->custom = $row['custom_fields'];
                }
            }
            if (!empty($fill)) {
                $data = [
                    'title' => $title,
                    'desc' => $desc,
                    'class' => $class,
                    'config' => $config,
                    'projects' => $fill,
                    'paginatehrml' => false,
                    'filter' => $filter,
                    'cols' => $layout,
                ];

                $response = view('projects::frontview.builder-sections.projects', compact('data'))->render();
            }
        } else {
            $response = '';
        }
        return $response;
    }

    public static function AllprojectHTML($title, $config, $layout, $class, $filter)
    {
        $response = '';
        if (File::exists(base_path() . '/packages/Powerpanel/Projects/src/Models/Projects.php') != null) {
            $fill = \Powerpanel\Projects\Models\Projects::getTemplateProjectsList();
            if (!empty($fill)) {
                $data = [
                    'title' => $title,
                    'config' => $config,
                    'projects' => $fill,
                    'class' => $class,
                    'paginatehrml' => true,
                    'cols' => $layout,
                    'filter' => $filter,
                ];
                $response = view('projects::frontview.builder-sections.projects', compact('data'))->render();
            }
        } else {
            $response = '';
        }
        return $response;
    }

    public static function organizationsHTML($title, $parentorg, $orgclass, $filter)
    {
        $response = '';

        $data = [
            'title' => $title,
            'orgclass' => $orgclass,
            'filter' => $filter,
        ];
        if (File::exists(base_path() . '/packages/Powerpanel/Organizations/src/Models/Organizations.php') != null) {
            $organizationData = \Powerpanel\Organizations\Models\Organizations::getBuilderOrganizations($parentorg);
            $orgdata = array();
            if (!empty($organizationData) && count($organizationData) > 0) {
                foreach ($organizationData as $orgnization) {

                    if ($orgnization['varDesignation'] != '') {
                        $designation = '<span class=\"desig-div\"><i class=\"fa fa-user-o\"></i>' . $orgnization['varDesignation'] . '</span>';
                    } else {
                        $designation = '';
                    }
                    $ogData = array();
                    $tempData = array();
                    $tempData['v'] = (String) $orgnization['id'];
                    $tempData['f'] = $orgnization['varTitle'] . $designation;
                    $ogData[] = $tempData;
                    if ($orgnization['intParentCategoryId'] > 0) {
                        array_push($ogData, (String) $orgnization['intParentCategoryId']);
                    } else {
                        array_push($ogData, null);
                    }
                    array_push($ogData, addslashes($orgnization['varTitle']));
                    $orgdata[] = $ogData;
                }
            }
            $orgdata = json_encode($orgdata);
            $data['orgdata'] = $orgdata;
            $data['orgclass'] = $orgclass;
            $response = view('organizations::frontview.builder-sections.organizations', compact('data', 'orgdata', 'orgclass'))->render();
        } else {
            $response = '';
        }
        return $response;
    }

    public static function departmentHTML($title, $records, $filter, $extraclass)
    {
        $response = '';
        $recIds = array_column($records, 'id');
        if (File::exists(base_path() . '/packages/Powerpanel/Department/src/Models/Department.php') != null) {
            $fill = \Powerpanel\Department\Models\Department::getBuilderDepartment($recIds);
            foreach ($records as $key => $row) {
                if (count(array_unique($row['custom_fields'])) > 1) {
                    $fill[($key - 1)]->custom = $row['custom_fields'];
                }
            }
            if (!empty($fill)) {
                $data = [
                    'title' => $title,
                    'department' => $fill,
                    'filter' => $filter,
                    'class' => $extraclass,
                ];
                $departmentArr = \Powerpanel\Department\Models\Department::getFrontList();
                $data['departmentArr'] = $departmentArr;
                $data['extraclass'] = $extraclass;

                $response = view('department::frontview.builder-sections.departments', compact('data'))->render();
            }
        } else {
            $response = '';
        }
        return $response;
    }

    public static function AlldepartmentHTML($title, $limit, $sdate, $edate, $class, $filter)
    {
        $response = '';
        if (File::exists(base_path() . '/packages/Powerpanel/Department/src/Models/Department.php') != null) {
            $fill = \Powerpanel\Department\Models\Department::getAllDepartment($limit, $sdate, $edate);
            if (!empty($fill)) {
                $data = [
                    'title' => $title,
                    'limit' => $limit,
                    'department' => $fill,
                    'class' => $class,
                    'filter' => $filter,
                ];
                $departmentArr = \Powerpanel\Department\Models\Department::getAllDepartment($limit, $sdate, $edate);
                $data['departmentArr'] = $departmentArr;
                $response = view('department::frontview.builder-sections.departments', compact('data'))->render();
            }
        } else {
            $response = '';
        }
        return $response;
    }

    public static function linksHTML($title, $records, $filter, $extraclass)
    {

        $response = '';
        $recIds = array_column($records, 'id');
        if (File::exists(base_path() . '/packages/Powerpanel/Links/src/Models/Links.php') != null) {
            $fill = \Powerpanel\Links\Models\Links::getBuilderLinks($recIds);
            foreach ($records as $key => $row) {
                if (count(array_unique($row['custom_fields'])) > 1) {
                    $fill[($key - 1)]->custom = $row['custom_fields'];
                }
            }

            if (!empty($fill)) {
                $data = [
                    'title' => $title,
                    'links' => $fill,
                    'filter' => $filter,
                    'class' => $extraclass,
                    'selectionlink' => 'Y',
                ];
                $response = view('links::frontview.builder-sections.links', compact('data'))->render();
            }
        } else {
            $response = '';
        }
        return $response;
    }

    public static function AllLinksHTML($title, $limit, $sdate, $edate, $class, $linkcat, $filter)
    {
        $response = '';
        if (File::exists(base_path() . '/packages/Powerpanel/LinksCategory/src/Models/LinksCategory.php') != null) {
            $fill = \Powerpanel\LinksCategory\Models\LinksCategory::getAllLinks($sdate, $edate);

            if (!empty($fill)) {
                $data = [
                    'title' => $title,
                    'limit' => $limit,
                    'links' => $fill,
                    'class' => $class,
                    'filter' => $filter,
                ];
//                echo '<pre>';print_r($data);exit;
                $response = view('links::frontview.builder-sections.links', compact('data'))->render();
            }
        } else {
            $response = '';
        }
        return $response;
    }

    public static function faqsHTML($title, $records, $filter, $extraclass)
    {
        $response = '';
        $recIds = array_column($records, 'id');
        if (File::exists(base_path() . '/packages/Powerpanel/Faq/src/Models/Faq.php') != null) {
            $fill = \Powerpanel\Faq\Models\Faq::getBuilderFaq($recIds);
            foreach ($records as $key => $row) {
                if (count(array_unique($row['custom_fields'])) > 1) {
                    $fill[($key - 1)]->custom = $row['custom_fields'];
                }
            }
            if (!empty($fill)) {
                $data = [
                    'title' => $title,
                    'faqs' => $fill,
                    'filter' => $filter,
                    'class' => $extraclass,
                ];
                $response = view('faq::frontview.builder-sections.faq', compact('data'))->render();
            }
        } else {
            $response = '';
        }
        return $response;
    }

    public static function AllFaqsHTML($title, $limit, $sdate, $edate, $class, $faqcat, $filter)
    {
        $response = '';

        if (File::exists(base_path() . '/packages/Powerpanel/Faq/src/Models/Faq.php') != null) {
            $fill = \Powerpanel\Faq\Models\Faq::getAllFaqs($faqcat, $limit, $sdate, $edate);

            if (!empty($fill)) {
                $data = [
                    'title' => $title,
                    'limit' => $limit,
                    'faqs' => $fill,
                    'class' => $class,
                    'filter' => $filter,
                ];
                $response = view('faq::frontview.builder-sections.faq', compact('data'))->render();
            }
        } else {
            $response = '';
        }
        return $response;
    }

    public static function AllpublicationHTML($title, $limit, $sector, $filter, $class, $publicationscat, $dbFilter, $sector_slug)
    {
        $response = '';
//        $recIds = array_column($records, 'id');

        if (File::exists(base_path() . '/packages/Powerpanel/Publications/src/Models/Publications.php') != null) {
            $fill = \Powerpanel\Publications\Models\Publications::getAllPublication($limit, $publicationscat, $dbFilter, $sector_slug);

            if (!empty($fill)) {
                $data = [
                    'title' => $title,
                    'limit' => $limit,
                    'publicationscat' => $publicationscat,
                    'publication' => $fill,

                    'paginatehrml' => true,
                    'filter' => $filter,
                    'class' => $class,
                ];
                $response = view('publications::frontview.builder-sections.publication', compact('data'))->render();
            }
        } else {
            $response = '';
        }
        return $response;
    }
    public static function AlldecisionHTML($title, $limit, $sector, $filter, $class, $decisioncat, $dbFilter, $sector_slug)
    {
        $response = '';
//        $recIds = array_column($records, 'id');

        if (File::exists(base_path() . '/packages/Powerpanel/Publications/src/Models/Publications.php') != null) {
            $fill = \Powerpanel\Decision\Models\Decision::getAllDecision($limit, $decisioncat, $dbFilter, $sector_slug);

            if (!empty($fill)) {
                $data = [
                    'title' => $title,
                    'limit' => $limit,
                    'decisioncat' => $decisioncat,
                    'decision' => $fill,

                    'paginatehrml' => true,
                    'filter' => $filter,
                    'class' => $class,
                ];
                $response = view('decision::frontview.builder-sections.decision', compact('data'))->render();
            }
        } else {
            $response = '';
        }

        return $response;
    }
    public static function AllformsandfeesHTML($title, $limit, $filter, $class, $dbFilter, $sector_slug)
    {
        $response = '';
//        $recIds = array_column($records, 'id');

        if (File::exists(base_path() . '/packages/Powerpanel/FormsAndFees/src/Models/FormsAndFees.php') != null) {
            $fill = \Powerpanel\FormsAndFees\Models\FormsAndFees::getAllFormsandfees($limit, $dbFilter, $sector_slug);
            if (!empty($fill)) {
                foreach ($fill as $description) {

                    $formDesc = self::renderBuilder($description->txtDescription);

                    $description->txtDescription = $formDesc['response'];
                }
            }
            if (!empty($fill)) {
                $data = [
                    'title' => $title,
                    'limit' => $limit,

                    'formsandfees' => $fill,

                    'filter' => $filter,
                    'class' => $class,
                ];
                $response = view('forms-and-fees::frontview.builder-sections.forms-and-fees', compact('data'))->render();
            }
        } else {
            $response = '';
        }
        return $response;
    }

    public static function blogsHTML($title, $desc, $config, $layout, $records, $filter, $extraclass)
    {
        $response = '';
        $recIds = array_column($records, 'id');
        $fields = Self::selectFields($config);
        if (File::exists(base_path() . '/packages/Powerpanel/Blogs/src/Models/Blogs.php') != null) {
            $fill = \Powerpanel\Blogs\Models\Blogs::getBuilderBlog($fields, $recIds);
            foreach ($records as $key => $row) {
                if (count(array_unique($row['custom_fields'])) > 1) {
                    $fill[($key - 1)]->custom = $row['custom_fields'];
                }
            }
            if (!empty($fill)) {
                $data = [
                    'title' => $title,
                    'desc' => $desc,
                    'blogs' => $fill,
                    'cols' => trim($layout),
                    'paginatehrml' => false,
                    'filter' => $filter,
                    'class' => $extraclass,
                ];
                $response = view('blogs::frontview.builder-sections.blog', compact('data'))->render();
            }
        } else {
            $response = '';
        }
        return $response;
    }

    public static function AllblogsHTML($title, $limit, $desc, $config, $layout, $filter, $class, $sdate, $edate, $blogscat)
    {
        $response = '';
//        $recIds = array_column($records, 'id');

        $fields = Self::selectFields($config);
        if (File::exists(base_path() . '/packages/Powerpanel/Blogs/src/Models/Blogs.php') != null) {
            $fill = \Powerpanel\Blogs\Models\Blogs::getAllBlogs($fields, $limit, $sdate, $edate, $blogscat);

            if (!empty($fill)) {
                $data = [
                    'title' => $title,
                    'limit' => $limit,
                    'desc' => $desc,
                    'blogs' => $fill,
                    'cols' => trim($layout),
                    'paginatehrml' => true,
                    'filter' => $filter,
                    'class' => $class,
                ];
                $response = view('blogs::frontview.builder-sections.blog', compact('data'))->render();
            }
        } else {
            $response = '';
        }
        return $response;
    }

    public static function AllservicesHTML($title, $config, $layout, $filter, $class)
    {
        $response = '';
//        $recIds = array_column($records, 'id');

        $fields = Self::selectFields($config);
        if (File::exists(base_path() . '/packages/Powerpanel/Services/src/Models/Services.php') != null) {
            $fill = \Powerpanel\Services\Models\Services::getTemplateServiceList($fields);

            if (!empty($fill)) {
                $data = [
                    'title' => $title,
                    'services' => $fill,
                    'cols' => trim($layout),
                    'filter' => $filter,
                    'paginatehrml' => true,
                    'class' => $class,
                ];
                $response = view('services::frontview.builder-sections.services', compact('data'))->render();
            }
        } else {
            $response = '';
        }
        return $response;
    }

    public static function serviceHTML($title, $desc, $config, $layout, $records, $filter, $extraclass)
    {
        $response = '';
        $recIds = array_column($records, 'id');
        $fields = Self::selectFields($config);
        if (File::exists(base_path() . '/packages/Powerpanel/Services/src/Models/Services.php') != null) {
            $fill = \Powerpanel\Services\Models\Services::getServiceList($fields, $recIds, 5);
            foreach ($records as $key => $row) {
                if (count(array_unique($row['custom_fields'])) > 1) {
                    $fill[($key - 1)]->custom = $row['custom_fields'];
                }
            }

            if (!empty($fill)) {
                $data = [
                    'title' => $title,
                    'desc' => $desc,
                    'services' => $fill,
                    'cols' => trim($layout),
                    'paginatehrml' => false,
                    'filter' => $filter,
                    'class' => $extraclass,
                ];
                $response = view('services::frontview.builder-sections.services', compact('data'))->render();
            }
        } else {
            $response = '';
        }
        return $response;
    }

    public static function photoalbumHTML($title, $desc, $config, $layout, $records, $filter, $extraclass)
    {
        $response = '';
        $recIds = array_column($records, 'id');
        $fields = Self::selectFields($config);
        if (File::exists(base_path() . '/packages/Powerpanel/PhotoAlbum/src/Models/PhotoAlbum.php') != null) {
            $fill = \Powerpanel\PhotoAlbum\Models\PhotoAlbum::getBuilderPhotoAlbum($fields, $recIds);
            foreach ($records as $key => $row) {
                if (count(array_unique($row['custom_fields'])) > 1) {
                    $fill[($key - 1)]->custom = $row['custom_fields'];
                }
            }
            if (!empty($fill)) {
                $data = [
                    'title' => $title,
                    'desc' => $desc,
                    'photoalbum' => $fill,
                    'cols' => trim($layout),
                    'paginatehrml' => false,
                    'filter' => $filter,
                    'class' => $extraclass,
                ];
                $response = view('photo-album::frontview.builder-sections.photoalbum', compact('data'))->render();
            }
        } else {
            $response = '';
        }
        return $response;
    }

    public static function AllphotoalbumHTML($title, $limit, $desc, $config, $layout, $filter, $class, $sdate, $edate)
    {
        $response = '';

        $fields = Self::selectFields($config);
        if (File::exists(base_path() . '/packages/Powerpanel/PhotoAlbum/src/Models/PhotoAlbum.php') != null) {
            $fill = \Powerpanel\PhotoAlbum\Models\PhotoAlbum::getAllPhotoAlbum($fields, $limit, $sdate, $edate);

            if (!empty($fill)) {
                $data = [
                    'title' => $title,
                    'limit' => $limit,
                    'desc' => $desc,
                    'photoalbum' => $fill,
                    'cols' => trim($layout),
                    'paginatehrml' => true,
                    'filter' => $filter,
                    'class' => $class,
                ];
                $response = view('photo-album::frontview.builder-sections.photoalbum', compact('data'))->render();
            }
        } else {
            $response = '';
        }
        return $response;
    }

    public static function videoalbumHTML($title, $desc, $config, $layout, $records, $filter, $extraclass)
    {
        $response = '';
        $recIds = array_column($records, 'id');
        $fields = Self::selectFields($config);
        if (File::exists(base_path() . '/packages/Powerpanel/VideoGallery/src/Models/VideoGallery.php') != null) {
            $fill = \Powerpanel\VideoGallery\Models\VideoGallery::getBuilderVideoGallery($fields, $recIds);
            foreach ($records as $key => $row) {
                if (count(array_unique($row['custom_fields'])) > 1) {
                    $fill[($key - 1)]->custom = $row['custom_fields'];
                }
            }
            if (!empty($fill)) {
                $data = [
                    'title' => $title,
                    'desc' => $desc,
                    'videogallery' => $fill,
                    'cols' => trim($layout),
                    'paginatehrml' => false,
                    'filter' => $filter,
                    'class' => $extraclass,
                ];
                $response = view('video-gallery::frontview.builder-sections.videogallery', compact('data'))->render();
            }
        } else {
            $response = '';
        }
        return $response;
    }

    public static function AllvideoalbumHTML($title, $limit, $desc, $config, $layout, $filter, $class, $sdate, $edate)
    {
        $response = '';

        $fields = Self::selectFields($config);
        if (File::exists(base_path() . '/packages/Powerpanel/VideoGallery/src/Models/VideoGallery.php') != null) {
            $fill = \Powerpanel\VideoGallery\Models\VideoGallery::getAllVideoGallery($fields, $limit, $sdate, $edate);

            if (!empty($fill)) {
                $data = [
                    'title' => $title,
                    'limit' => $limit,
                    'desc' => $desc,
                    'videogallery' => $fill,
                    'cols' => trim($layout),
                    'paginatehrml' => true,
                    'filter' => $filter,
                    'class' => $class,
                ];
                $response = view('video-gallery::frontview.builder-sections.videogallery', compact('data'))->render();
            }
        } else {
            $response = '';
        }
        return $response;
    }

    public static function WelComeHTML($title, $image, $content, $alignment, $src)
    {
        $response = '';
        $data = [
            'title' => $title,
            'image' => $image,
            'content' => $content,
            'alignment' => $alignment,
            'src' => $src,
        ];
        $response = view('visualcomposer::frontview.builder-sections.home.welcome', compact('data'))->render();
        return $response;
    }

    public static function OnlyTitleHTML($content, $extclass)
    {
        $response = '';
        $data = [
            'title' => $content,
            'extclass' => $extclass,
        ];
        $response = view('visualcomposer::frontview.builder-sections.cms', compact('data'))->render();

        return $response;
    }

    public static function OnlyIframeHTML($content, $extclass)
    {
        $response = '';
        $data = [
            'iframe' => $content,
            'extclass' => $extclass,
        ];
        $response = view('visualcomposer::frontview.builder-sections.iframe', compact('data'))->render();

        return $response;
    }

    public static function TwoColumnsHTML($content, $type, $subtype, $partitionclass, $two)
    {
        $response = '';

        if ($type == 'formdata') {
            $formdata = \App\CommonModel::getFormBuilderData($content['id']);
            if (isset($formdata->varFormDescription)) {
                $form_data_json = json_decode($formdata->varFormDescription, true);
                $data = [
                    'formid' => $content['id'],
                    'title' => $content,
                    'Description' => $formdata->Description,
                    'formtitle' => $formdata->FormTitle,
                    'formTotalDetails' => $formdata,
                    'formdata' => $form_data_json,
                    'type' => $type,
                    'subtype' => $subtype,
                    'Columns' => $two,
                ];
            } else {
                $data = [
                    'formid' => $content['id'],
                    'title' => $content,
                    'Description' => '',
                    'formtitle' => '',
                    'formTotalDetails' => $formdata,
                    'formdata' => '',
                    'type' => $type,
                    'subtype' => $subtype,
                    'Columns' => $two,
                ];
            }
        } else {
            $data = [
                'content' => $content,
                'type' => $type,
                'subtype' => $subtype,
                'partitionclass' => $partitionclass,
                'Columns' => $two,
            ];
        }
        $response = view('visualcomposer::frontview.builder-sections.twopart', compact('data'))->render();

        return $response;
    }

    public static function OneThreeColumnsHTML($content, $type, $subtype, $partitionclass, $two)
    {
        $response = '';
        if ($type == 'formdata') {
            $formdata = \App\CommonModel::getFormBuilderData($content['id']);
            if (isset($formdata->varFormDescription)) {
                $form_data_json = json_decode($formdata->varFormDescription, true);
                $data = [
                    'formid' => $content['id'],
                    'title' => $content,
                    'Description' => $formdata->Description,
                    'formtitle' => $formdata->FormTitle,
                    'formTotalDetails' => $formdata,
                    'formdata' => $form_data_json,
                    'type' => $type,
                    'subtype' => $subtype,
                    'Columns' => $two,
                ];
            } else {
                $data = [
                    'formid' => $content['id'],
                    'title' => $content,
                    'Description' => '',
                    'formtitle' => '',
                    'formTotalDetails' => $formdata,
                    'formdata' => '',
                    'type' => $type,
                    'subtype' => $subtype,
                    'Columns' => $two,
                ];
            }
        } else {
            $data = [
                'content' => $content,
                'type' => $type,
                'subtype' => $subtype,
                'partitionclass' => $partitionclass,
                'Columns' => $two,
            ];
        }
        $response = view('visualcomposer::frontview.builder-sections.oneThreepart', compact('data'))->render();

        return $response;
    }

    public static function ThreeOneColumnsHTML($content, $type, $subtype, $partitionclass, $two)
    {
        $response = '';
        if ($type == 'formdata') {
            $formdata = \App\CommonModel::getFormBuilderData($content['id']);
            if (isset($formdata->varFormDescription)) {
                $form_data_json = json_decode($formdata->varFormDescription, true);
                $data = [
                    'formid' => $content['id'],
                    'title' => $content,
                    'Description' => $formdata->Description,
                    'formtitle' => $formdata->FormTitle,
                    'formTotalDetails' => $formdata,
                    'formdata' => $form_data_json,
                    'type' => $type,
                    'subtype' => $subtype,
                    'Columns' => $two,
                ];
            } else {
                $data = [
                    'formid' => $content['id'],
                    'title' => $content,
                    'Description' => '',
                    'formtitle' => '',
                    'formTotalDetails' => $formdata,
                    'formdata' => '',
                    'type' => $type,
                    'subtype' => $subtype,
                    'Columns' => $two,
                ];
            }
        } else {
            $data = [
                'content' => $content,
                'type' => $type,
                'subtype' => $subtype,
                'partitionclass' => $partitionclass,
                'Columns' => $two,
            ];
        }
        $response = view('visualcomposer::frontview.builder-sections.threeOnepart', compact('data'))->render();

        return $response;
    }

    public static function ThreeColumnsHTML($content, $type, $subtype, $partitionclass, $three)
    {

        $response = '';
        if ($type == 'formdata') {
            $formdata = \App\CommonModel::getFormBuilderData($content['id']);
            if (isset($formdata->varFormDescription)) {
                $form_data_json = json_decode($formdata->varFormDescription, true);
                $data = [
                    'formid' => $content['id'],
                    'title' => $content,
                    'Description' => $formdata->Description,
                    'formtitle' => $formdata->FormTitle,
                    'formTotalDetails' => $formdata,
                    'formdata' => $form_data_json,
                    'type' => $type,
                    'subtype' => $subtype,
                    'Columns' => $three,
                ];
            } else {

                $data = [
                    'formid' => $content['id'],
                    'title' => $content,
                    'Description' => '',
                    'formtitle' => '',
                    'formTotalDetails' => $formdata,
                    'formdata' => '',
                    'type' => $type,
                    'subtype' => $subtype,
                    'Columns' => $three,
                ];
            }
        } else {

            $data = [
                'content' => $content,
                'type' => $type,
                'subtype' => $subtype,
                'partitionclass' => $partitionclass,
                'Columns' => $three,

            ];
//            echo '<pre>';print_r($data);

        }

        $response = view('visualcomposer::frontview.builder-sections.threepart', compact('data'))->render();
        return $response;
    }

    public static function FourColumnsHTML($content, $type, $subtype, $partitionclass, $four)
    {
        $response = '';
        if ($type == 'formdata') {
            $formdata = \App\CommonModel::getFormBuilderData($content['id']);
            if (isset($formdata->varFormDescription)) {
                $form_data_json = json_decode($formdata->varFormDescription, true);
                $data = [
                    'formid' => $content['id'],
                    'title' => $content,
                    'Description' => $formdata->Description,
                    'formtitle' => $formdata->FormTitle,
                    'formTotalDetails' => $formdata,
                    'formdata' => $form_data_json,
                    'type' => $type,
                    'subtype' => $subtype,
                    'Columns' => $four,
                ];
            } else {
                $data = [
                    'formid' => $content['id'],
                    'title' => $content,
                    'Description' => '',
                    'formtitle' => '',
                    'formTotalDetails' => $formdata,
                    'formdata' => '',
                    'type' => $type,
                    'subtype' => $subtype,
                    'Columns' => $four,
                ];
            }
        } else {
            $data = [
                'content' => $content,
                'type' => $type,
                'subtype' => $subtype,
                'partitionclass' => $partitionclass,
                'Columns' => $four,
            ];
        }
        $response = view('visualcomposer::frontview.builder-sections.fourpart', compact('data'))->render();

        return $response;
    }

    public static function OnlyFormBuilderHTML($formid, $content, $extclass)
    {
        $response = '';
        $formdata = \App\CommonModel::getFormBuilderData($formid);
        if (isset($formdata->varFormDescription)) {
            $form_data_json = json_decode($formdata->varFormDescription, true);
            $data = [
                'formid' => $formid,
                'title' => $content,
                'Description' => $formdata->Description,
                'formtitle' => $formdata->FormTitle,
                'formTotalDetails' => $formdata,
                'formdata' => $form_data_json,
                'extclass' => $extclass,
            ];
            $response = view('visualcomposer::frontview.builder-sections.formbuilder', compact('data'))->render();
        } else {
            $data = [
                'formid' => $formid,
                'title' => $content,
                'Description' => '',
                'formtitle' => '',
                'formTotalDetails' => $formdata,
                'formdata' => '',
                'extclass' => $extclass,
            ];
            $response = view('visualcomposer::frontview.builder-sections.formbuilder')->render();
        }
        return $response;
    }

    public static function OnlyContentHTML($content, $class)
    {

        $response = '';
        $data = [
            'content' => $content,
            'extclass' => $class,
        ];
        $response = view('visualcomposer::frontview.builder-sections.cms', compact('data'))->render();

        return $response;
    }

    public static function TwoContentHTML($leftcontent, $rightcontent)
    {
        $response = '';
        $data = [
            'leftcontent' => $leftcontent,
            'rightcontent' => $rightcontent,
        ];
        $response = view('visualcomposer::frontview.builder-sections.cms', compact('data'))->render();

        return $response;
    }

    public static function ImageHTML($title, $img, $image, $alignment, $extraclass)
    {
        $response = '';
        $data = [
            'title' => $title,
            'img' => $img,
            'image' => $image,
            'extra_class' => $extraclass,
            'alignment' => $alignment,
        ];
        $response = view('visualcomposer::frontview.builder-sections.cms', compact('data'))->render();

        return $response;
    }

    public static function DocumentHTML($document)
    {
        $response = '';
        $data = [
            'document' => $document['document'],
            'caption' => $document['caption'],
            'doc_date_time' => $document['doc_date_time'],
        ];
        $response = view('visualcomposer::frontview.builder-sections.cms', compact('data'))->render();

        return $response;
    }

    public static function ContentHTML($title, $content, $alignment, $src, $image)
    {
        $response = '';
        $data = [
            'title' => $title,
            'content' => $content,
            'alignment' => $alignment,
            'src' => $src,
            'image' => $image,
        ];
        $response = view('visualcomposer::frontview.builder-sections.cms', compact('data'))->render();

        return $response;
    }

    public static function rowHTML($content, $filter = false)
    {

        $response = '';
        if (isset($filter) && !empty($filter)) {
            $records = array();
            foreach ($content['val'] as $key => $value) {
                foreach ($value['columns'] as $valueKey => $columnValue) {
                    if (isset($columnValue['elementObj']['val']['doc_date_time']) && !empty($columnValue['elementObj']['val']['doc_date_time'])) {
                        $isYear = true;
                        if (isset($filter['year']) && !empty($filter['year'])) {
                            $isYear = false;
                            if (in_array(date("Y", strtotime($columnValue['elementObj']['val']['doc_date_time'])), $filter['year'])) {
                                $isYear = true;
                            }
                        }
                        $isMonth = true;
                        if (isset($filter['month']) && !empty($filter['month'])) {
                            $isMonth = false;
                            if (date("m", strtotime($columnValue['elementObj']['val']['doc_date_time'])) == $filter['month']) {
                                $isMonth = true;
                            }
                        }

                        if ($isYear && $isMonth) {
                            array_push($records, $columnValue['elementObj']);

                        } else {
                            unset($content['val'][$key]['columns'][$valueKey]);
                        }

                    }

                }
            }

        }
        $data = ['content' => $content];
        $response = view('visualcomposer::frontview.builder-sections.row_template', compact('data'))->render();
        return $response;
    }

    public static function VideoHTML($title, $videoType, $vidId)
    {
        $response = '';
        if ($videoType == 'YouTube') {
            $data = [
                'title' => $title,
                'videoType' => 'YouTube',
                'vidId' => $vidId,
            ];
        } else {
            $data = [
                'title' => $title,
                'videoType' => 'Vimeo',
                'vidId' => $vidId,
            ];
        }
        $response = view('visualcomposer::frontview.builder-sections.cms', compact('data'))->render();

        return $response;
    }

    public static function VideoContentHTML($title, $videoType, $vidId, $content, $alignment)
    {
        $response = '';
        if ($videoType == 'YouTube') {
            $data = [
                'videotitle' => $title,
                'videoType' => 'YouTube',
                'vidId' => $vidId,
                'content' => $content,
                'videoalignment' => $alignment,
            ];
        } else {
            $data = [
                'videotitle' => $title,
                'videoType' => 'Vimeo',
                'vidId' => $vidId,
                'content' => $content,
                'videoalignment' => $alignment,
            ];
        }
        $response = view('visualcomposer::frontview.builder-sections.cms', compact('data'))->render();

        return $response;
    }

    public static function MapHTML($latitude, $longitude)
    {
        $response = '';

        $data = [
            'latitude' => $latitude,
            'longitude' => $longitude,
        ];
        $response = view('visualcomposer::frontview.builder-sections.cms', compact('data'))->render();

        return $response;
    }

    public static function ConatctInfoHTML($content, $section_address, $section_email, $section_phone)
    {
        $response = '';

        $data = [
            'othercontent' => $content,
            'section_address' => $section_address,
            'section_email' => $section_email,
            'section_phone' => $section_phone,
        ];
        $response = view('visualcomposer::frontview.builder-sections.cms', compact('data'))->render();

        return $response;
    }

    public static function CustomSectionHTML($title, $layout, $records, $class)
    {
        $response = '';

        $data = [

            'title' => $title,

            'layout' => $layout,
            'records' => $records,
            'class' => $class,

        ];

        $response = view('visualcomposer::frontview.builder-sections.customsection', compact('data'))->render();
        return $response;
    }
    public static function ButtonHTML($title, $content, $alignment, $target)
    {
        $response = '';

        $data = [
            'title' => $title,
            'content' => $content,
            'alignment' => $alignment,
            'target' => $target,
        ];
        $response = view('visualcomposer::frontview.builder-sections.cms', compact('data'))->render();

        return $response;
    }

    public static function SpacerHTML($config)
    {
        $response = '';
        if ($config == '9') {
            $cdata = 'ac-pt-xs-0';
        } elseif ($config == '10') {
            $cdata = 'ac-pt-xs-5';
        } elseif ($config == '11') {
            $cdata = 'ac-pt-xs-10';
        } elseif ($config == '12') {
            $cdata = 'ac-pt-xs-15';
        } elseif ($config == '13') {
            $cdata = 'ac-pt-xs-20';
        } elseif ($config == '14') {
            $cdata = 'ac-pt-xs-25';
        } elseif ($config == '15') {
            $cdata = 'ac-pt-xs-30';
        } elseif ($config == '16') {
            $cdata = 'ac-pt-xs-40';
        } elseif ($config == '17') {
            $cdata = 'ac-pt-xs-50';
        } else {
            $cdata = '';
        }
        $data = [
            'config' => $cdata,
        ];
        $response = view('visualcomposer::frontview.builder-sections.spacer', compact('data'))->render();

        return $response;
    }

    public static function selectFields($config)
    {
        switch ($config) {
            case 1:
                return ['moduleFields' => ['id', 'intAliasId', 'fkIntImgId', 'varTitle']];
                break;
            case 2:
                return ['moduleFields' => ['id', 'intAliasId', 'fkIntImgId', 'varTitle', 'varShortDescription']];
                break;
            case 3:
                return ['moduleFields' => ['id', 'varTitle', 'dtDateTime', 'dtEndDateTime']];
                break;
            case 4:
                return ['moduleFields' => ['id', 'intAliasId', 'fkIntImgId', 'varTitle', 'dtDateTime', 'dtEndDateTime']];
                break;
            case 5:
                return ['moduleFields' => ['id', 'intAliasId', 'fkIntImgId', 'varTitle', 'varShortDescription', 'dtDateTime', 'dtEndDateTime']];
                break;
            case 6:
                return ['moduleFields' => ['id', 'intAliasId', 'varTitle', 'varShortDescription']];
                break;
            case 7:
                return ['moduleFields' => ['id', 'intAliasId', 'varTitle', 'dtDateTime', 'dtEndDateTime']];
                break;
            case 8:
                return ['moduleFields' => ['id', 'intAliasId', 'varTitle', 'varShortDescription', 'dtDateTime', 'dtEndDateTime']];
                break;
            case 9:
                return ['moduleFields' => ['id', 'intAliasId', 'varTitle', 'varShortDescription', 'varDepartment', 'varTagLine']];
                break;
            default:
                return ['*'];
                break;
        }
    }

}
