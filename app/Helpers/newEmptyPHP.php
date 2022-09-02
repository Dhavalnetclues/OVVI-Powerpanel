else if ($module == 'formarea') {
                                $formid = $section1['val']['id'];
                                $content = $section1['val']['content'];
                                $extclass = '';
                                $response .= Self::OnlyFormBuilderHTML($formid, $content, $extclass);
                            } else if ($module == 'image') {
                                $title = $section1['val']['title'];
                                $image = $section1['val']['image'];
                                $alignment = $section1['val']['alignment'];
                                $img = $section1['val']['src'];
                                $response .= Self::ImageHTML($title, $img, $image, $alignment);
                            } else if ($module == 'document') {
                                $document = $section1['val']['document'];
                                $img = $section1['val']['src'];
                                $response .= Self::DocumentHTML($document, $img);
                            } else if ($module == 'textarea') {
                                $content = $section1['val']['content'];
                                $response .= Self::OnlyContentHTML($content);
                            } else if ($module == 'twocontent') {
                                $leftcontent = $section1['val']['leftcontent'];
                                $rightcontent = $section1['val']['rightcontent'];
                                $response .= Self::TwoContentHTML($leftcontent, $rightcontent);
                            } else if ($module == 'only_video') {
                                $title = $section1['val']['title'];
                                $videoType = $section1['val']['videoType'];
                                $vidId = $section1['val']['vidId'];
                                $response .= Self::VideoHTML($title, $videoType, $vidId);
                            } else if ($module == 'video_content') {
                                $title = $section1['val']['title'];
                                $videoType = $section1['val']['videoType'];
                                $vidId = $section1['val']['vidId'];
                                $content = $section1['val']['content'];
                                $alignment = $section1['val']['alignment'];
                                $response .= Self::VideoContentHTML($title, $videoType, $vidId, $content, $alignment);
                            } else if ($module == 'spacer_template') {
                                $config = $section1['val']['config'];
                                $response .= Self::SpacerHTML($config);
                            } else if ($module == 'img_content') {
                                $title = $section1['val']['title'];
                                $content = $section1['val']['content'];
                                $alignment = $section1['val']['alignment'];
                                $image = $section1['val']['image'];
                                $src = $section1['val']['src'];
                                $response .= Self::ContentHTML($title, $content, $alignment, $src, $image);
                            } else if ($module == 'organizations_template') {
                                $title = $section1['val']['title'];
                                $parentorg = $section1['val']['parentorg'];
                                $orgclass = $section1['val']['orgclass'];
                                $filter = $section1['val']['template'];
                                $response .= Self::organizationsHTML($title, $parentorg, $orgclass, $filter);
                            } else if ($module == 'alerts') {
                                $title = $section1['val']['title'];
                                $records = $section1['val']['records'];
                                $filter = $section1['val']['template'];
                                $extraclass = $section1['val']['extraclass'];
                                $response .= Self::alertsHTML($title, $records, $filter, $extraclass);
                            } else if ($module == 'alerts_template') {
                                $title = $section1['val']['title'];
                                $limit = $section1['val']['limit'];
                                $alerttype = $section1['val']['alerttype'];
                                $sdate = $section1['val']['sdate'];
                                $edate = $section1['val']['edate'];
                                $class = $section1['val']['class'];
                                $filter = $section1['val']['template'];
                                $response .= Self::AllalertsHTML($title, $limit, $alerttype, $sdate, $edate, $class, $filter);
                            } else if ($module == 'department') {
                                $title = $section1['val']['title'];
                                $records = $section1['val']['records'];
                                $filter = $section1['val']['template'];
                                $extraclass = $section1['val']['extraclass'];
                                $response .= Self::departmentHTML($title, $records, $filter, $extraclass);
                            } else if ($module == 'department_template') {
                                $title = $section1['val']['title'];
                                $limit = $section1['val']['limit'];
                                $sdate = $section1['val']['sdate'];
                                $edate = $section1['val']['edate'];
                                $class = $section1['val']['class'];
                                $filter = $section1['val']['template'];
                                $response .= Self::AlldepartmentHTML($title, $limit, $sdate, $edate, $class, $filter);
                            } else if ($module == 'photoalbum') {
                                $title = $section1['val']['title'];
                                $config = $section1['val']['config'];
                                $desc = $section1['val']['desc'];
                                $layout = $section1['val']['layout'];
                                $records = $section1['val']['records'];
                                $filter = $section1['val']['template'];
                                $extraclass = $section1['val']['extraclass'];
                                $response .= Self::photoalbumHTML($title, $desc, $config, $layout, $records, $filter, $extraclass);
                            } else if ($module == 'photoalbum_template') {
                                $title = $section1['val']['title'];
                                $limit = $section1['val']['limit'];
                                $desc = $section1['val']['desc'];
                                $config = $section1['val']['config'];
                                $layout = $section1['val']['layout'];
                                $filter = $section1['val']['template'];
                                $class = $section1['val']['class'];
                                $sdate = $section1['val']['sdate'];
                                $edate = $section1['val']['edate'];
                                $response .= Self::AllphotoalbumHTML($title, $limit, $desc, $config, $layout, $filter, $class, $sdate, $edate);
                            } else if ($module == 'videoalbum') {
                                $title = $section1['val']['title'];
                                $config = $section1['val']['config'];
                                $desc = $section1['val']['desc'];
                                $layout = $section1['val']['layout'];
                                $records = $section1['val']['records'];
                                $filter = $section1['val']['template'];
                                $response .= Self::videoalbumHTML($title, $desc, $config, $layout, $records, $filter);
                            } else if ($module == 'videoalbum_template') {
                                $title = $section1['val']['title'];
                                $limit = $section1['val']['limit'];
                                $desc = $section1['val']['desc'];
                                $config = $section1['val']['config'];
                                $layout = $section1['val']['layout'];
                                $filter = $section1['val']['template'];
                                $class = $section1['val']['class'];
                                $sdate = $section1['val']['sdate'];
                                $edate = $section1['val']['edate'];
                                $response .= Self::AllvideoalbumHTML($title, $limit, $desc, $config, $layout, $filter, $class, $sdate, $edate);
                            } else if ($module == 'events') {
                                $title = $section1['val']['title'];
                                $config = $section1['val']['config'];
                                $desc = $section1['val']['desc'];
                                $layout = $section1['val']['layout'];
                                $records = $section1['val']['records'];
                                $filter = $section1['val']['template'];
                                $extraclass = $section1['val']['extraclass'];
                                $response .= Self::eventHTML($title, $desc, $config, $layout, $records, $filter, $extraclass);
                            } else if ($module == 'events_template') {
                                $title = $section1['val']['title'];
                                $limit = $section1['val']['limit'];
                                $desc = $section1['val']['desc'];
                                $config = $section1['val']['config'];
                                $layout = $section1['val']['layout'];
                                $filter = $section1['val']['template'];
                                $class = $section1['val']['class'];
                                $sdate = $section1['val']['sdate'];
                                $edate = $section1['val']['edate'];
                                $eventscat = $section1['val']['eventscat'];
                                $response .= Self::AlleventsHTML($title, $limit, $desc, $config, $layout, $filter, $class, $sdate, $edate, $eventscat);
                            } else if ($module == 'blogs') {
                                $title = $section1['val']['title'];
                                $config = $section1['val']['config'];
                                $desc = $section1['val']['desc'];
                                $layout = $section1['val']['layout'];
                                $records = $section1['val']['records'];
                                $filter = $section1['val']['template'];
                                $extraclass = $section1['val']['extraclass'];
                                $response .= Self::blogsHTML($title, $desc, $config, $layout, $records, $filter, $extraclass);
                            } else if ($module == 'blogs_template') {
                                $title = $section1['val']['title'];
                                $limit = $section1['val']['limit'];
                                $desc = $section1['val']['desc'];
                                $config = $section1['val']['config'];
                                $layout = $section1['val']['layout'];
                                $filter = $section1['val']['template'];
                                $class = $section1['val']['class'];
                                $sdate = $section1['val']['sdate'];
                                $edate = $section1['val']['edate'];
                                $blogscat = $section1['val']['blogscat'];
                                $response .= Self::AllblogsHTML($title, $limit, $desc, $config, $layout, $filter, $class, $sdate, $edate, $blogscat);
                            } else if ($module == 'service') {
                                $title = $section1['val']['title'];
                                $config = $section1['val']['config'];
                                if (isset($section1['val']['desc']) && $section1['val']['desc'] != '') {
                                    $desc = $section1['val']['desc'];
                                } else {
                                    $desc = '';
                                }
                                $layout = $section1['val']['layout'];
                                $records = $section1['val']['records'];
                                $filter = $section1['val']['template'];
                                if (isset($section1['val']['extraclass']) && $section1['val']['extraclass'] != '') {
                                    $extraclass = $section1['val']['extraclass'];
                                } else {
                                    $extraclass = '';
                                }
                                $response .= Self::serviceHTML($title, $desc, $config, $layout, $records, $filter, $extraclass);
                            } else if ($module == 'service_template') {
                                $title = $section1['val']['title'];
                                $config = $section1['val']['config'];
                                $layout = $section1['val']['layout'];
                                $filter = $section1['val']['template'];
                                if (isset($section1['val']['extclass']) && $section1['val']['extclass'] != '') {
                                    $class = $section1['val']['extclass'];
                                } else {
                                    $class = '';
                                }
                                $response .= Self::AllservicesHTML($title, $config, $layout, $filter, $class);
                            } else if ($module == 'news') {
                                $title = $section1['val']['title'];
                                $config = $section1['val']['config'];
                                $desc = $section1['val']['desc'];
                                $layout = $section1['val']['layout'];
                                $records = $section1['val']['records'];
                                $filter = $section1['val']['template'];
                                $extraclass = $section1['val']['extraclass'];
                                $response .= Self::newsHTML($title, $desc, $config, $layout, $records, $filter, $extraclass);
                            } else if ($module == 'news_template') {
                                $title = $section1['val']['title'];
                                $limit = $section1['val']['limit'];
                                $desc = $section1['val']['desc'];
                                $config = $section1['val']['config'];
                                $layout = $section1['val']['layout'];
                                $filter = $section1['val']['template'];
                                $class = $section1['val']['class'];
                                $sdate = $section1['val']['sdate'];
                                $edate = $section1['val']['edate'];
                                $newscat = $section1['val']['newscat'];
                                $response .= Self::AllnewsHTML($title, $limit, $desc, $config, $layout, $filter, $class, $sdate, $edate, $newscat);
                            } else if ($module == 'links') {
                                $title = $section1['val']['title'];
                                $records = $section1['val']['records'];
                                $filter = $section1['val']['template'];
                                $extraclass = $section1['val']['extraclass'];
                                $response .= Self::linksHTML($title, $records, $filter, $extraclass);
                            } else if ($module == 'link_template') {
                                $title = $section1['val']['title'];
                                $limit = $section1['val']['limit'];
                                $sdate = $section1['val']['sdate'];
                                $edate = $section1['val']['edate'];
                                $class = $section1['val']['class'];
                                $linkcat = $section1['val']['linkcat'];
                                $filter = $section1['val']['template'];
                                $response .= Self::AllLinksHTML($title, $limit, $sdate, $edate, $class, $linkcat, $filter);
                            } else if ($module == 'faqs') {
                                $title = $section1['val']['title'];
                                $records = $section1['val']['records'];
                                $filter = $section1['val']['template'];
                                $extraclass = $section1['val']['extraclass'];
                                $response .= Self::faqsHTML($title, $records, $filter, $extraclass);
                            } else if ($module == 'faq_template') {
                                $title = $section1['val']['title'];
                                $limit = $section1['val']['limit'];
                                $sdate = $section1['val']['sdate'];
                                $edate = $section1['val']['edate'];
                                $class = $section1['val']['class'];
                                $faqcat = $section1['val']['faqcat'];
                                $filter = $section1['val']['template'];
                                $response .= Self::AllFaqsHTML($title, $limit, $sdate, $edate, $class, $faqcat, $filter);
                            } else if ($module == 'publication') {
                                $title = $section1['val']['title'];
                                $config = $section1['val']['config'];
                                $desc = $section1['val']['desc'];
                                $layout = $section1['val']['layout'];
                                $records = $section1['val']['records'];
                                $filter = $section1['val']['template'];
                                $extraclass = $section1['val']['extraclass'];
                                $response .= Self::publicationHTML($title, $desc, $config, $layout, $records, $filter, $extraclass);
                            } else if ($module == 'publication_template') {
                                $title = $section1['val']['title'];
                                $limit = $section1['val']['limit'];
                                $desc = $section1['val']['desc'];
                                $config = $section1['val']['config'];
                                $sdate = $section1['val']['sdate'];
                                $edate = $section1['val']['edate'];
                                $class = $section1['val']['class'];
                                $publicationscat = $section1['val']['publicationscat'];
                                $layout = $section1['val']['layout'];
                                $filter = $section1['val']['template'];
                                $response .= Self::AllpublicationHTML($title, $limit, $desc, $config, $layout, $filter, $sdate, $edate, $class, $publicationscat);
                            } else if ($module == 'home-img_content') {
                                $title = $section1['val']['title'];
                                $image = $section1['val']['image'];
                                $content = $section1['val']['content'];
                                $alignment = $section1['val']['alignment'];
                                $src = $section1['val']['src'];
                                $response .= Self::WelComeHTML($title, $image, $content, $alignment, $src);
                            } else if ($module == 'map') {
                                $latitude = $section1['val']['latitude'];
                                $longitude = $section1['val']['longitude'];
                                $response .= Self::MapHTML($latitude, $longitude);
                            } else if ($module == 'conatct_info') {
                                $content = $section1['val']['content'];
                                $section_address = $section1['val']['section_address'];
                                $section_email = $section1['val']['section_email'];
                                $section_phone = $section1['val']['section_phone'];
                                $response .= Self::ConatctInfoHTML($content, $section_address, $section_email, $section_phone);
                            } else if ($module == 'button_info') {
                                $title = $section1['val']['title'];
                                $content = $section1['val']['content'];
                                $alignment = $section1['val']['alignment'];
                                $target = $section1['val']['target'];
                                $response .= Self::ButtonHTML($title, $content, $alignment, $target);
                            }