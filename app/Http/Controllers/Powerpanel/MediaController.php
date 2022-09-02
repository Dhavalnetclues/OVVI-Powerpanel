<?php

namespace Powerpanel\ShieldCMSTheme\Controllers\Powerpanel\powerpanelcontroller;

use App\Audio;
use App\AudioModuleRel;
use App\CommonModel;
use App\Document;
use App\DocumentModuleRel;
use App\Helpers\Aws_File_helper;
use App\Helpers\ImageConvertor;
use App\Helpers\MyLibrary;
use App\Helpers\resize_image;
use App\Http\Controllers\PowerpanelController;
use App\Http\Traits\slug;
use App\Image;
use App\ImgModuleRel;
use App\Video;
use App\VideoModuleRel;
use Auth;
use Carbon\Carbon;
use Config;
use DB;
use File;
use Illuminate\Routing\UrlGenerator;
use Image as InterventionImage;
use Request;

class MediaController extends PowerpanelController
{

    public $_APP_URL;
    protected $url;

    /**
     * Create a new controller instance.
     * @return void
     */
    public function __construct(UrlGenerator $url)
    {
        $this->url = $url->to('/');
        //$this->_APP_URL = Config::get('Constant.ENV_APP_URL');
        $this->_APP_URL = Config::get('Constant.CDN_PATH');
        /* if($this->BUCKET_ENABLED){
        $this->_APP_URL = Config::get('Constant.CDN_PATH');
        }else{
        $this->_APP_URL = Config::get('Constant.ENV_APP_URL');
        } */
        parent::__construct();
        if (isset($_COOKIE['locale'])) {
            app()->setLocale($_COOKIE['locale']);
        }
    }

    public function set_image_html()
    {
        $html = '<div class="title_section"><h2>Upload Image</h2>';
        $html .= '<div class="pull-right form_fld_title_right">
                   <label class="label-title">Folder Select</label>
                   <span id="folderreplace_1">
                   <select class="form-control folderslection" onchange="Imagefolderselect(this.value)">';
        $folderdata = Image::getFolderType('1');
        $html .= '<option value="0">All</option>';
        foreach ($folderdata as $fdata) {
            $html .= '<option value="' . $fdata->id . '">' . $fdata->foldername . '</option>';
        }
        $html .= '</select></span>
        <a href="javascript:;" class="btn btn-green-drake FoldeCreatePopup" id="">Folder Create</a>
         </div></div>';
        $html .= '<div class="portlet light">
										<div class="scroller">
											<div class="row">
												<div class="col-md-12">
													<form name="filename"  enctype="multipart/form-data" class="drop_border dropzone dropzone-file-area" id="my-dropzone">
														<input type="hidden" class="img_folder_id" id="folderid" name="folderid" value="">
                                                                                                                <div class="dz-message needsclick">
															<div class="dropzone_icon">
																<i class="icon-cloud-upload icons"></i>
															</div>
															<h3 class="sbold">Drop files here or click to upload image</h3>
															<p>Select file to upload</p>
														</div>
													</form>
													<br/>
													<div class="text-center">
														<a href="javascript:;" onclick="MediaManager.setMyUploadTab(' . Auth::user()->id . ')" class="btn btn-green-drake">Go to User Gallery</a>
														<br/>
														<br/>
														<p><strong>Note:</strong> You can upload 15 images at one time and maximum upload file size is 15MB per image.</p>
													</div>
												</div>
											</div>
											<div class="clearfix"></div>
										</div>
									</div>';
        $html .= '<div class="new_modal modal FoldeCreatePopupModel fade bs-modal-md" id="FoldeCreatePopupModel" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Folder Create</h5>
                            <button type="button" class="btn-close fs-10" data-bs-dismiss="modal" aria-label="Close"> </button>
                        </div>
                        <div class="modal-body replybody"><div class="row">
                                <div class="col-md-12">
                                    <div class="form-group cm-floating">
                                        <label for="to">Folder Name: <span aria-required="true" class="required"> * </span></label>
                                        <input type="text" class="form-control" name="foldername"  id="foldername" value="">
                                        <input type="hidden" class="form-control" name="foldertype"  id="foldertype" value="1">
                                        <div class="success"></div>
                                        <div class="errorField"></div>
                                        <div class="error help-desk  mb-0"></div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary bg-gradient waves-effect waves-light btn-label foldercreateids" id="" value="saveandexit"><div class="flex-shrink-0"><i class="ri-send-plane-line label-icon align-middle fs-20 me-2"></i></div> Submit</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>';
        echo $html;
        exit;
    }

    public function set_video_html()
    {

        $html = '<div class="title_section"><h2>Upload Video</h2></div>
									<div class="portlet light">
										<div class="scroller gallery">
											<div class="row">
												<div class="col-md-12">
													<form name="filename"  enctype="multipart/form-data" class="drop_border dropzone dropzone-file-area" id="my-dropzone-video">
														<div class="dz-message needsclick">
															<div class="dropzone_icon">
																<i class="icon-cloud-upload icons"></i>
															</div>
															<h3 class="sbold">Drop files here or click to upload video</h3>
															<p>Select file to upload</p>
														</div>
													</form>
													<br/>
													<div class="text-center">
														<a href="javascript:;" onclick="MediaManager.setMyVideosTab(' . Auth::user()->id . ')" class="btn btn-green-drake">Go to Video Gallery</a>
														<br/>
														<br/>
														<p><strong>Note:</strong> You can upload 15 videos at one time and maximum upload file size is 10MB.</p>
													</div>
												</div>
											</div>
										</div>
									</div>';

        echo $html;
        exit;
    }

    public function get_video_byUrl_html()
    {
        $html = '<div class="title_section"><h2>Insert video from youtube url</h2></div>\n\
							<div class="portlet light">
								<div class="form-group">
										<label class="form_title" for="varMediaVideoUrlType">Video Url Type<span aria-required="true" class="required"> * </span></label>
										<div class="md-radio-inline">
											<div class="md-radio">
												<input type="radio" checked value="youtube" id="video_youtube" name="varMediaVideoUrlType" class="md-radiobtn mediaradio">
												<label for="video_youtube">
													<span class="inc"></span>
													<span class="check"></span>
													<span class="box"></span> Youtube
												</label>
											</div>
											<div class="md-radio">
												<input type="radio" value="vimeo" id="video_vimeo" name="varMediaVideoUrlType" class="md-radiobtn mediaradio">
												<label for="video_vimeo">
													<span class="inc"></span>
													<span class="check"></span>
													<span class="box"></span> Vimeo
												</label>
											</div>
										</div>
									</div>
								<div class="row">
									<div class="col-md-12">
										<div class="form-group form-md-line-input has-info">
											<label class="form_title">Enter video url<span aria-required="true" class="required"> * </span></label>
											<input type="text" class="form-control input-lg video_url" id="form_control_1">
											<span class="thrownError" style="color:red"></span>
										</div>
										<a href="javascript:void(0);" onclick="MediaManager.insertVideoFromUrl()" class="btn btn-green-drake">Upload Video</a>
										<a href="javascript:void(0);" onclick="MediaManager.setMyVideosTab(' . Auth::user()->id . ')" class="btn btn-green-drake">Go to Video Gallery</a><br/>
										<p></p>
									</div>
								</div>
							</div>
						</div>';
        echo $html;
        exit;
    }

    public function set_document_uploader()
    {
        $html = '<div class="title_section"><h2>Upload Document(s)</h2>';
        $html .= '<div class="pull-right form_fld_title_right">
                   <label class="label-title">Folder Select</label>
                   <span id="folderreplace_2">
                   <select class="form-control folderslection" onchange="Documentfolderselect(this.value)" >';
        $folderdata = Document::getFolderType('2');
        $html .= '<option value="0">All</option>';
        foreach ($folderdata as $fdata) {
            $html .= '<option value="' . $fdata->id . '">' . $fdata->foldername . '</option>';
        }
        $html .= '</select></span>
        <a href="javascript:;" class="btn btn-green-drake FoldeCreatePopup" id="">Folder Create</a>
         </div></div>';
        $html .= '<div class="portlet light">
                <div class="scroller">
                    <div class="row">
                        <div class="col-md-12">
                            <form name="filename"  enctype="multipart/form-data" class="drop_border dropzone dropzone-file-area" id="my-dropzone-document">
                                <input type="hidden" class="doc_folder_id" id="folderid" name="folderid" value="">
                                    <div class="dz-message needsclick">
                                    <div class="dropzone_icon">
                                        <i class="icon-cloud-upload icons"></i>
                                    </div>
                                    <h3 class="sbold">Drop files here or click to upload document(s)</h3>
                                    <p>Select file to upload</p>
                                </div>
                            </form>
                            <br/>
                            <div class="text-center">
                                <a href="javascript:;" onclick="MediaManager.setDocumentListTab(' . Auth::user()->id . ')" class="btn btn-green-drake">Go to User Document Gallery</a>
                                <br/>
                                <br/>
                                <p><strong>Note:</strong> You can upload 15 document(s) at one time and maximum upload file size is 45 MB per document.</p>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>';
        $html .= '<div class="new_modal modal FoldeCreatePopupModel fade bs-modal-md" id="FoldeCreatePopupModel" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Folder Create</h5>
                            <button type="button" class="btn-close fs-10" data-bs-dismiss="modal" aria-label="Close"> </button>
                        </div>
                        <div class="modal-body replybody"><div class="row">
                                <div class="col-md-12">
                                    <div class="form-group cm-floating">
                                        <label for="to">Folder Name: <span aria-required="true" class="required"> * </span></label>
                                        <input type="text" class="form-control" name="foldername"  id="foldername" value="">
                                        <input type="hidden" class="form-control" name="foldertype"  id="foldertype" value="2">
                                        <div class="success"></div>
                                        <div class="error help-desk mb-0"></div>
                                    </div>                                    
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary bg-gradient waves-effect waves-light btn-label foldercreateids" id="" value="saveandexit"><div class="flex-shrink-0"><i class="ri-send-plane-line label-icon align-middle fs-20 me-2"></i></div> Submit</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </div>';
        echo $html;
        exit;
    }

    public function set_audio_uploader()
    {
        $html = '<div class="title_section"><h2>Upload Audio(s)</h2>';
        $html .= '<div class="pull-right form_fld_title_right">
                   <label class="label-title">Folder Select</label>
                   <span id="folderreplace_3">
                   <select class="form-control folderslection" onchange="Audiofolderselect(this.value)">';
        $folderdata = Audio::getFolderType('3');
        $html .= '<option value="0">All</option>';
        foreach ($folderdata as $fdata) {
            $html .= '<option value="' . $fdata->id . '">' . $fdata->foldername . '</option>';
        }
        $html .= '</select></span>
        <a href="javascript:;" class="btn btn-green-drake FoldeCreatePopup" id="">Folder Create</a>
         </div></div>';
        $html .= '<div class="portlet light">
				<div class="scroller">
					<div class="row">
						<div class="col-md-12">
							<form name="filename"  enctype="multipart/form-data" class="drop_border dropzone dropzone-file-area" id="my-dropzone-audio">
                                                                                <input type="hidden" class="audio_folder_id" id="folderid" name="folderid" value="">
								<div class="dz-message needsclick">
									<div class="dropzone_icon">
										<i class="icon-cloud-upload icons"></i>
									</div>
									<h3 class="sbold">Drop files here or click to upload audio(s)</h3>
									<p>Select file to upload</p>
								</div>
							</form>
							<br/>
							<div class="text-center">
								<a href="javascript:;" onclick="MediaManager.setAudioListTab(' . Auth::user()->id . ')" class="btn btn-green-drake">Go to User Audio Gallery</a>
								<br/>
								<br/>
								<p><strong>Note:</strong> You can upload 15 audio(s) at one time and maximum upload file size is 45 MB per audio.</p>
							</div>
						</div>
					</div>
					<div class="clearfix"></div>
				</div>
			</div>';
        $html .= '<div class="new_modal modal FoldeCreatePopupModel fade bs-modal-md" id="FoldeCreatePopupModel" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Folder Create</h5>
                            <button type="button" class="btn-close fs-10" data-bs-dismiss="modal" aria-label="Close"> </button>
                        </div>
                        <div class="modal-body replybody"><div class="row">
                                <div class="col-md-12">
                                    <div class="form-group cm-floating">
                                        <label for="to">Folder Name: <span aria-required="true" class="required"> * </span></label>
                                        <input type="text" class="form-control" name="foldername"  id="foldername" value="">
                                        <input type="hidden" class="form-control" name="foldertype"  id="foldertype" value="3">
                                        <div class="success"></div>
                                        <div class="error help-desk mb-0"></div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary bg-gradient waves-effect waves-light btn-label foldercreateids" id="" value="saveandexit"><div class="flex-shrink-0"><i class="ri-send-plane-line label-icon align-middle fs-20 me-2"></i></div> Submit</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>';
        echo $html;
        exit;
    }

    public function upload_image()
    {
        $respose = false;
        if (Request::file('file') || null !== Request::file('upload')) {

            $file = (null !== Request::file('upload')) ? Request::file('upload') : Request::file('file');

            if (exif_imagetype($file->getPathName())) {
                $timestamp = str_replace([' ', ':'], '-', Carbon::now()->toDateTimeString());
                $pathinfo = pathinfo($file->getClientOriginalName());

                $name = $timestamp . '-' . self::clean($pathinfo['filename']);

                $sourceFilePath = $file->getPathName();
                $storeFileName = $name . '.' . $pathinfo['extension'];
                $imageArr = array();
                $imageArr['fkIntUserId'] = Auth::user()->id;
                if (isset($_REQUEST['folderid']) && $_REQUEST['folderid'] != '') {
                    $imageArr['fk_folder'] = $_REQUEST['folderid'];
                    $folderdata = Image::getFolderName($_REQUEST['folderid']);

                    if ($this->BUCKET_ENABLED) {
                        Aws_File_helper::putObject($sourceFilePath, $this->S3_MEDIA_BUCKET_PATH . '/', $storeFileName);
                    } else {
                        $file->move(Config::get('Constant.LOCAL_CDN_PATH') . '/assets/images/' . $folderdata->foldername . '/', $name . '.' . $pathinfo['extension']);
//                        copy(Config::get('Constant.LOCAL_CDN_PATH') . '/assets/images/' . $folderdata->foldername . '/'. $name . '.' . $pathinfo['extension'],Config::get('Constant.LOCAL_CDN_PATH') . '/assets/images/'. $name . '.' . $pathinfo['extension']);
                    }
                    $imageArr['varfolder'] = 'folder';
                } else {
                    $imageArr['fk_folder'] = '0';
                    $imageArr['varfolder'] = '';
                    if ($this->BUCKET_ENABLED) {
                        Aws_File_helper::putObject($sourceFilePath, $this->S3_MEDIA_BUCKET_PATH . '/', $storeFileName);
                    } else {
                        $file->move(Config::get('Constant.LOCAL_CDN_PATH') . '/assets/images/', $name . '.' . $pathinfo['extension']);
                    }
                }
                $imageArr['txtImageName'] = trim($name);
                $imageArr['txtImgOriginalName'] = trim($pathinfo['filename']);
                $imageArr['varImageExtension'] = $pathinfo['extension'];
                $imageArr['chrIsUserUploaded'] = 'Y';
                $imageArr['created_at'] = Carbon::now();

                $imageID = CommonModel::addRecord($imageArr, '\\App\\Image');
                if (isset($imageArr['fk_folder']) && $imageArr['fk_folder'] != 0) {
                    $response = 'folder';
                } else {
                    $response = $imageID;
                }

                if (null !== Request::file('upload')) {
                    $response = json_encode(["uploaded" => true, 'url' => resize_image::resize($response)]);
                    return $response;
                }
            } else {
                $response = "You can't upload files of this type";
                if (null !== Request::file('upload')) {
                    $response = json_encode(["uploaded" => false, 'url' => '']);
                    return $response;
                }
            }
        } else {
            $response = 'File Not Found';
        }
        echo $response;
        exit;
    }

    public function upload_video()
    {

        $respose = false;
        if (Request::file('file')) {
            $file = Request::file('file');

            $timestamp = str_replace([' ', ':'], '-', Carbon::now()->toDateTimeString());
            $pathinfo = pathinfo($file->getClientOriginalName());

            $name = $timestamp . '-' . self::clean($pathinfo['filename']);

            $sourceFilePath = $file->getPathName();
            $storeFileName = $name . '.' . $pathinfo['extension'];
            if ($this->BUCKET_ENABLED) {
                Aws_File_helper::putObject($sourceFilePath, $this->S3_MEDIA_BUCKET_VIDEO_PATH . '/', $storeFileName);
            } else {
                $file->move(Config::get('Constant.LOCAL_CDN_PATH') . '/assets/videos/', $name . '.' . $pathinfo['extension']);
            }

            $slug = slug::create_slug($name);
            $user = Auth::user();

            $video = new Video;
            $video->fkIntUserId = $user->id;
            $video->varVideoExtension = $pathinfo['extension'];
            $video->varVideoName = $name;
            $video->txtVideoOriginalName = trim($pathinfo['filename']);
            $video->chrIsUserUploaded = 'Y';
            $video->save();

            $response = $video->id;
        } else {
            $response = 'File Not Found';
        }

        echo $response;
        exit;
    }

    public function upload_documents()
    {
        $respose = false;
        $allowedExtentions = array('pdf', 'doc', 'docx', 'ppt', 'xls', 'txt', 'xlsx', 'zip');
        if (Request::file('file')) {
            $file = Request::file('file');
            $pathinfo = pathinfo($file->getClientOriginalName());
            $sourceFilePath = $file->getPathName();
            $checkvalidmimeType = MyLibrary::check_doc_mime($sourceFilePath);
            $documentsFieldsArr = array();
            if ($checkvalidmimeType && in_array(strtolower($pathinfo['extension']), $allowedExtentions)) {
                $timestamp = str_replace([' ', ':'], '-', Carbon::now()->toDateTimeString());
                $name = $timestamp . '-' . self::clean($pathinfo['filename']);
                $storeFileName = $name . '.' . $pathinfo['extension'];

                if (isset($_REQUEST['folderid']) && $_REQUEST['folderid'] != '') {
                    $documentsFieldsArr['fk_folder'] = $_REQUEST['folderid'];
                    $folderdata = Document::getFolderName($_REQUEST['folderid']);
                    if ($this->BUCKET_ENABLED) {
                        Aws_File_helper::putObject($sourceFilePath, $this->S3_MEDIA_BUCKET_DOCUMENT_PATH . '/', $storeFileName);
                    } else {
                        $file->move(Config::get('Constant.LOCAL_CDN_PATH') . '/documents/' . $folderdata->foldername . '/', $name . '.' . $pathinfo['extension']);
                    }
                    $documentsFieldsArr['varfolder'] = 'folder';
                } else {
                    $documentsFieldsArr['fk_folder'] = '0';
                    $documentsFieldsArr['varfolder'] = '';
                    if ($this->BUCKET_ENABLED) {
                        Aws_File_helper::putObject($sourceFilePath, $this->S3_MEDIA_BUCKET_DOCUMENT_PATH . '/', $storeFileName);
                    } else {
                        $file->move(Config::get('Constant.LOCAL_CDN_PATH') . '/documents/', $name . '.' . $pathinfo['extension']);
                    }
                }
                $removeMultispaceTerm = preg_replace('!\s+!', ' ', $pathinfo['filename']);
                $saveorginalName = preg_replace('/[^A-Za-z0-9\-. ]/', '', $removeMultispaceTerm);

                $documentsFieldsArr['fkIntUserId'] = Auth::user()->id;
                $documentsFieldsArr['txtDocumentName'] = $saveorginalName;
                $documentsFieldsArr['txtSrcDocumentName'] = trim($name);
                $documentsFieldsArr['varDocumentExtension'] = $pathinfo['extension'];
                $documentsFieldsArr['chrIsUserUploaded'] = 'Y';
                $documentsFieldsArr['created_at'] = Carbon::now();

                $documentID = CommonModel::addRecord($documentsFieldsArr, '\\App\\Document');

                if (isset($documentsFieldsArr['fk_folder']) && $documentsFieldsArr['fk_folder'] != 0) {
                    $response = "folder";
                } else {
                    $response = $documentID;
                }
            } else {
                $response['error'] = 'Please upload valid file with valid extention and mime type.';
            }
        } else {
            $response['error'] = 'File Not Found';
        }
        echo $response;
        exit;
    }

    public function upload_audios()
    {
        $respose = false;
        if (Request::file('file')) {
            $file = Request::file('file');

            $timestamp = str_replace([' ', ':'], '-', Carbon::now()->toDateTimeString());
            $pathinfo = pathinfo($file->getClientOriginalName());

            $name = $timestamp . '-' . self::clean($pathinfo['filename']);

            $sourceFilePath = $file->getPathName();
            $storeFileName = $name . '.' . $pathinfo['extension'];
            $audiosFieldsArr = array();
            if (isset($_REQUEST['folderid']) && $_REQUEST['folderid'] != '') {
                $audiosFieldsArr['fk_folder'] = $_REQUEST['folderid'];
                $folderdata = Audio::getFolderName($_REQUEST['folderid']);
                if ($this->BUCKET_ENABLED) {
                    Aws_File_helper::putObject($sourceFilePath, $this->S3_MEDIA_BUCKET_AUDIO_PATH . '/', $storeFileName);
                } else {
                    $file->move(Config::get('Constant.LOCAL_CDN_PATH') . '/audios/' . $folderdata->foldername . '/', $name . '.' . $pathinfo['extension']);
                }
                $audiosFieldsArr['varfolder'] = 'folder';
            } else {
                $audiosFieldsArr['fk_folder'] = '0';
                $audiosFieldsArr['varfolder'] = '';
                if ($this->BUCKET_ENABLED) {
                    Aws_File_helper::putObject($sourceFilePath, $this->S3_MEDIA_BUCKET_AUDIO_PATH . '/', $storeFileName);
                } else {
                    $file->move(Config::get('Constant.LOCAL_CDN_PATH') . '/audios/', $name . '.' . $pathinfo['extension']);
                }
            }
            $removeMultispaceTerm = preg_replace('!\s+!', ' ', $pathinfo['filename']);
            $saveorginalName = preg_replace('/[^A-Za-z0-9\-. ]/', '', $removeMultispaceTerm);

            $audiosFieldsArr['fkIntUserId'] = Auth::user()->id;
            $audiosFieldsArr['txtAudioName'] = $saveorginalName;
            $audiosFieldsArr['txtSrcAudioName'] = trim($name);
            $audiosFieldsArr['varAudioExtension'] = $pathinfo['extension'];
            $audiosFieldsArr['chrIsUserUploaded'] = 'Y';
            $audiosFieldsArr['created_at'] = Carbon::now();

            $audioID = CommonModel::addRecord($audiosFieldsArr, '\\App\\Audio');
            if (isset($audiosFieldsArr['fk_folder']) && $audiosFieldsArr['fk_folder'] != 0) {
                $response = "folder";
            } else {
                $response = $audioID;
            }
        } else {
            $response = 'File Not Found';
        }
        echo $response;
        exit;
    }

    public function user_uploaded_image()
    {
        $response = array();
        if (Request::get('userid')) {
            $user_id = Request::get('userid');
            $imageName = (null !== Request::get('imageName')) ? Request::get('imageName') : '';
            $limit = 28;
            $page = 1;
            $filterArr = array();
            $filterArr['imageName'] = $imageName;
            $images = Image::getImages($limit, $page, 0, $filterArr);
            $Image_html = $this->getImageHtml($images, 'User Gallery', $filterArr, $limit);
            $response['Image_html'] = $Image_html;
            $response['imageCount'] = count($images);
        }

        echo json_encode($response);
        exit;
    }

    public function folder_uploaded_image()
    {
        $response = array();
        if (Request::get('userid')) {
            $user_id = Request::get('userid');
            $imageName = (null !== Request::get('imageName')) ? Request::get('imageName') : '';
            $limit = 28;
            $page = 1;
            $filterArr = array();
            $filterArr['imageName'] = $imageName;
            $images = Image::getImages($limit, $page, 0, $filterArr);
            $Image_html = $this->getFolderImageHtml($images, 'User Gallery', $filterArr, $limit);
            $response['Image_html'] = $Image_html;
            $response['imageCount'] = count($images);
        }

        echo json_encode($response);
        exit;
    }

    public function load_more_docs($userid = false)
    {
        $response = false;
        $item_per_page = 50;

        $page_number = filter_var(Request::post('page'), FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH);
        if (!is_numeric($page_number)) {
            header('HTTP/1.1 500 Invalid page number!');
            exit();
        }
        $position = (($page_number - 1) * $item_per_page) + 1;
        $docsHtml = '';
        $filterArr = array();
        if (null !== Request::post('DocName') && Request::post('DocName') != "") {
            $filterArr['docName'] = Request::post('DocName');
        }

        $allDocCount = Document::getRecordCount($filterArr);
        $total_pages = ceil($allDocCount / $item_per_page);

        $more_document = Document::getDocuments($item_per_page, $page_number, $position, $filterArr);
        if ($more_document->count() > 0) {
            foreach ($more_document as $key => $value) {
                if ($this->BUCKET_ENABLED) {
                    $documentPath = $this->S3_MEDIA_BUCKET_DOCUMENT_PATH . '/' . $value->txtSrcDocumentName . '.' . $value->varDocumentExtension;
                } else {
                    $documentPath = Config::get('Constant.LOCAL_CDN_PATH') . '/documents/' . $value->txtSrcDocumentName . '.' . $value->varDocumentExtension;
                }

                if ($this->filePathExist($documentPath)) {
                    if ($this->BUCKET_ENABLED) {
                        $docUrl = $this->_APP_URL . $this->S3_MEDIA_BUCKET_DOCUMENT_PATH . '/' . $value->txtSrcDocumentName . '.' . $value->varDocumentExtension;
                    } else {
                        $docUrl = Config::get('Constant.CDN_PATH') . '/documents/' . $value->txtSrcDocumentName . '.' . $value->varDocumentExtension;
                    }
                    $docsHtml .= "<div data-docext='" . $value->varDocumentExtension . "' data-docnm='" . $value->txtDocumentName . "' data-docUrl=" . $docUrl . " class='img-box contains_thumb' id='document_" . $value->id . "'>
																											 <div class='thumbnail_container'>
																													<div class='thumbnail'>
																													 <a  title='" . $value->txtDocumentName . "' href='javascript:void(0);' onclick=\"MediaManager.selectDocument('" . $value->id . "')\" >";
                    if (strtolower($value->varDocumentExtension) == "pdf") {
                        $docsHtml .= "<img alt='" . $value->txtDocumentName . "' src='" . $this->_APP_URL . "assets/images/documents_logo/pdf.png'><span class=\"mob_doc\"><i data-bs-toggle='tooltip' data-bs-placement='top' class='ri-cellphone-line' title='View: " . $value->intMobileViewCount . "\nDownload: " . $value->intMobileDownloadCount . "'></i></span><span class=\"mob_desk\"><i data-bs-toggle='tooltip' data-bs-placement='top' class='ri-computer-line' title='View: " . $value->intDesktopViewCount . "\nDownload: " . $value->intDesktopDownloadCount . "'></i></span><span class=\"mob_copy\"><i class='ri-clipboard-line doc-copy' data-docurl=" . $docUrl . " data-bs-toggle='tooltip' data-bs-placement='top' title='Copy Link'></i></span>";
                    } elseif (strtolower($value->varDocumentExtension) == "xls" || $value->varDocumentExtension == "xlsx" || $value->varDocumentExtension == "xlsm") {
                        $docsHtml .= "<img alt='" . $value->txtDocumentName . "' src='" . $this->_APP_URL . "assets/images/documents_logo/xls.png'><span class=\"mob_doc\"><i data-bs-toggle='tooltip' data-bs-placement='top' class='ri-cellphone-line' title='View: " . $value->intMobileViewCount . "\nDownload: " . $value->intMobileDownloadCount . "'></i></span><span class=\"mob_desk\"><i data-bs-toggle='tooltip' data-bs-placement='top' class='ri-computer-line' title='View: " . $value->intDesktopViewCount . "\nDownload: " . $value->intDesktopDownloadCount . "'></i></span><span class=\"mob_copy\"><i class='ri-clipboard-line doc-copy' data-docurl=" . $docUrl . " data-bs-toggle='tooltip' data-bs-placement='top' title='Copy Link'></i></span>";
                    } elseif (strtolower($value->varDocumentExtension) == "docx" || strtolower($value->varDocumentExtension) == "doc") {
                        $docsHtml .= "<img alt='" . $value->txtDocumentName . "' src='" . $this->_APP_URL . "assets/images/documents_logo/doc.png'><span class=\"mob_doc\"><i data-bs-toggle='tooltip' data-bs-placement='top' class='ri-cellphone-line' title='View: " . $value->intMobileViewCount . "\nDownload: " . $value->intMobileDownloadCount . "'></i></span><span class=\"mob_desk\"><i data-bs-toggle='tooltip' data-bs-placement='top' class='ri-computer-line' title='View: " . $value->intDesktopViewCount . "\nDownload: " . $value->intDesktopDownloadCount . "'></i></span><span class=\"mob_copy\"><i class='ri-clipboard-line doc-copy' data-docurl=" . $docUrl . " data-bs-toggle='tooltip' data-bs-placement='top' title='Copy Link'></i></span>";
                    } elseif (strtolower($value->varDocumentExtension) == "ppt") {
                        $docsHtml .= "<img alt='" . $value->txtDocumentName . "' src='" . $this->_APP_URL . "assets/images/documents_logo/ppt.png'><span class=\"mob_doc\"><i data-bs-toggle='tooltip' data-bs-placement='top' class='ri-cellphone-line' title='View: " . $value->intMobileViewCount . "\nDownload: " . $value->intMobileDownloadCount . "'></i></span><span class=\"mob_desk\"><i data-bs-toggle='tooltip' data-bs-placement='top' class='ri-computer-line' title='View: " . $value->intDesktopViewCount . "\nDownload: " . $value->intDesktopDownloadCount . "'></i></span><span class=\"mob_copy\"><i class='ri-clipboard-line doc-copy' data-docurl=" . $docUrl . " data-bs-toggle='tooltip' data-bs-placement='top' title='Copy Link'></i></span>";
                    } elseif (strtolower($value->varDocumentExtension) == "txt") {
                        $docsHtml .= "<img alt='" . $value->txtDocumentName . "' src='" . $this->_APP_URL . "assets/images/documents_logo/txt.png'><span class=\"mob_doc\"><i data-bs-toggle='tooltip' data-bs-placement='top' class='ri-cellphone-line' title='View: " . $value->intMobileViewCount . "\nDownload: " . $value->intMobileDownloadCount . "'></i></span><span class=\"mob_desk\"><i data-bs-toggle='tooltip' data-bs-placement='top' class='ri-computer-line' title='View: " . $value->intDesktopViewCount . "\nDownload: " . $value->intDesktopDownloadCount . "'></i></span><span class=\"mob_copy\"><i class='ri-clipboard-line doc-copy' data-docurl=" . $docUrl . " data-bs-toggle='tooltip' data-bs-placement='top' title='Copy Link'></i></span>";
                    } else {
                        $docsHtml .= "<img alt='" . $value->txtDocumentName . "' src='" . $this->_APP_URL . "assets/images/documents_logo/document_icon.png'><span class=\"mob_doc\"><i data-bs-toggle='tooltip' data-bs-placement='top' class='ri-cellphone-line' title='View: " . $value->intMobileViewCount . "\nDownload: " . $value->intMobileDownloadCount . "'></i></span><span class=\"mob_desk\"><i data-bs-toggle='tooltip' data-bs-placement='top' class='ri-computer-line' title='View: " . $value->intDesktopViewCount . "\nDownload: " . $value->intDesktopDownloadCount . "'></i></span><span class=\"mob_copy\"><i class='ri-clipboard-line doc-copy' data-docurl=" . $docUrl . " data-bs-toggle='tooltip' data-bs-placement='top' title='Copy Link'></i></span>";
                    }

                    $docsHtml .= "<span class='icon-check' aria-hidden='true'></span>
																								</a>
																								</div>
																						</div>
															<div class='title-change'>
																<input class='form-control' type='text' name='documentname" . $value->id . "' id='documentname_" . $value->id . "' value='" . $value->txtDocumentName . "'/><a onclick=\"MediaManager.GetUpdateDocumentName('" . $value->id . "')\" href=\"javascript:void(0);\" class='btn'><i class='fa fa-pencil'></i></a>
															</div>
															<a class='right_check' href='javascript:void(0)' ><i class=''></i></a>
															</div>";
                }
            }
        }

        $response = array('currentpage' => Request::post('page'));
        $response['doc_box'] = $docsHtml;
        $response['docCount'] = count($more_document);
        $response['lastpage'] = $total_pages;
        echo json_encode($response);
        exit;
    }

    public function load_more_images($userid = false)
    {

        $response = false;
        $item_per_page = 28;
        $page_number = filter_var(Request::post('page'), FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH);

        if (!is_numeric($page_number)) {
            header('HTTP/1.1 500 Invalid page number!');
            exit();
        }

        $position = (($page_number - 1) * $item_per_page) + 1;
        $Image_html = '';

        $filterArr = array();
        if (null !== Request::post('imageName') && Request::post('imageName') != "") {
            $filterArr['imageName'] = Request::post('imageName');
        }
        $allImageCount = Image::getRecordCount($filterArr);
        $total_pages = ceil($allImageCount / $item_per_page);
        $more_images = Image::getImages($item_per_page, $page_number, $position, $filterArr);

        if ($more_images->count() > 0) {

            foreach ($more_images as $key => $value) {
                if ($this->BUCKET_ENABLED) {
                    $img_path = $this->S3_MEDIA_BUCKET_PATH . '/' . $value->txtImageName . '.' . $value->varImageExtension;
                } else {
                    $img_path = Config::get('Constant.LOCAL_CDN_PATH') . '/assets/images/' . $value->txtImageName . '.' . $value->varImageExtension;
                }

                if ($this->filePathExist($img_path)) {
                    $image_url = resize_image::resize($value->id);
                    $Image_html .= "<div class='img-box contains_thumb' id='media_" . $value->id . "'>
																			 <div class='thumbnail_container'>
																					<div class='thumbnail' id='media_image_" . $value->id . "'>
																					 <a  title='" . $value->txtImgOriginalName . "' href='javascript:void(0);' onclick=\"MediaManager.selectImage('" . $value->id . "')\" >
																									 <img alt='" . $value->txtImgOriginalName . "' src='" . $image_url . "'>
																									 <span class='icon-check' aria-hidden='true'></span>
																							</a>
																							</div>
																					</div>
																					<a class='right_check' href='javascript:void(0)' ><i class=''></i></a>";
                    $Image_html .= "<div class='img-btns'>";
                    $Image_html .= "<a href='javascript:;' title='Image Detail' onclick=\"MediaManager.getImageDetails('" . $value->id . "');\"><span class='icon icon-info'></span></a>";

                    if ($value->varImageExtension != "svg") {
                        $Image_html .= "<a href='javascript:;' title='Crop Image' onclick=\"MediaManager.cropImage('" . $value->id . "');\"><span class='icon-crop'></span></a>";
                    }

                    $Image_html .= "</div></div>";
                }
            }
        }

        $response = array('currentpage' => Request::post('page'));
        $response['image_box'] = $Image_html;
        $response['imageCount'] = count($more_images);
        $response['lastpage'] = $total_pages;

        echo json_encode($response);
        exit;
    }

    public function user_uploaded_video()
    {
        $response = false;
        $vidoeHtml = '';
        if (Request::get('userid')) {
            $user_id = Request::get('userid');
            $limit = 18;
            $page = 1;
            $videos = Video::getRecords()->publish()->deleted()->take($limit, $page)->orderBy('id', 'DESC')->get();
            $vidoeHtml .= '<div class="title_section">
							<h2>Video Gallery</h2>';
            $vidoeHtml .= '<div class="pull-right">';
            if ($videos->count() > 0) {
                $vidoeHtml .= '<a class="btn btn-green-drake" id="insert_video" onclick="MediaManager.insertVideo();" href="javascript:void(0);" style="padding:4px 12px">Insert Media</a>&nbsp;';
                $vidoeHtml .= '<a style="padding:4px 12px;margin-right:10px;" class="btn btn-green-drake" id="delete_video" onclick=\'MediaManager.openConfirmBox("video");\' href="javascript:void(0);" >Delete</a>';
            }
            $vidoeHtml .= '</div></div><div class="clearfix"></div>';
            if ($videos->count() > 0) {
                $vidoeHtml .= '<div class="portlet light">
									<div class="scroller gallery">
										<div id="append_user_image">';
                foreach ($videos as $key => $value) {
                    if (isset($value->youtubeId) && !empty($value->youtubeId) && $value->varMediaVideoUrlType == "youtube") {
                        $vidoeHtml .= "<div class='img-box video_thumb contains_thumb' data-video_name='" . $value->varVideoName . "' id='video_" . $value->id . "' data-video_type='youtube' data-video_source='" . $value->youtubeId . "'>
												<div class='thumbnail_container'>
													<div class='thumbnail' id='media_image_" . $value->id . "' >
														<img src='http://img.youtube.com/vi/" . $value->youtubeId . "/default.jpg' />
													</div>
												</div>
												<a class='right_check' href='javascript:void(0)' ><i class=''></i></a>
												<div class='video_overflow'>
													<a title='" . $value->varVideoName . "' href='http://www.youtube.com/embed/" . $value->youtubeId . "?autoplay=1' class='link icns_set' data-fancybox><span class='fa fa-play'></span></a>
													<button title='Please select video and click on Insert Media button' class='icns_set' onclick=\"MediaManager.selectVideo('" . $value->id . "')\">
													<span class='fa fa-hand-pointer-o'></span>
													</button>
												</div>
											</div>";
                    } else if (isset($value->vimeoId) && !empty($value->vimeoId) && $value->varMediaVideoUrlType == "vimeo") {
                        $vimgid = $value->vimeoId;
                        $hash = unserialize(file_get_contents("http://vimeo.com/api/v2/video/$vimgid.php"));
                        $vimeo_has = $this->_APP_URL . "resources/images/video_thumb_icon.png";
                        if (isset($hash[0]['thumbnail_medium'])) {
                            $vimeo_has = $hash[0]['thumbnail_medium'];
                        }
                        $vidoeHtml .= "<div class='img-box video_thumb contains_thumb' data-video_name='" . $value->varVideoName . "' id='video_" . $value->id . "' data-video_type='vimeo' data-video_source='" . $value->vimeoId . "'>
													<div class='thumbnail_container'>
															<div class='thumbnail' id='media_image_" . $value->id . "' >
																	<img src='" . $vimeo_has . "' />
															</div>
													</div>
													<a class='right_check' href='javascript:void(0)' ><i class=''></i></a>
													<div class='video_overflow'>
															<a title='" . $value->varVideoName . "' href='https://player.vimeo.com/video/" . $value->vimeoId . "' class='link icns_set' data-fancybox><span class='fa fa-play'></span></a>
															<button title='Please select video and click on Insert Media button' class='icns_set' onclick=\"MediaManager.selectVideo('" . $value->id . "')\">
															<span class='fa fa-hand-pointer-o'></span>
															</button>
													</div>
											</div>";
                    } else {
                        if ($this->BUCKET_ENABLED) {
                            $video_path = $this->S3_MEDIA_BUCKET_VIDEO_PATH . '/' . $value->varVideoName . '.' . $value->varVideoExtension;
                            $videoUrl = $this->_APP_URL . $this->S3_MEDIA_BUCKET_VIDEO_PATH . '/' . $value->varVideoName . '.' . $value->varVideoExtension;
                        } else {
                            $video_path = Config::get('Constant.LOCAL_CDN_PATH') . '/assets/videos/' . $value->varVideoName . '.' . $value->varVideoExtension;
                            $videoUrl = Config::get('Constant.CDN_PATH') . '/assets/videos/' . $value->varVideoName . '.' . $value->varVideoExtension;
                        }

                        if ($this->filePathExist($video_path)) {
                            $vidoeHtml .= "<div class='img-box video_thumb contains_thumb' data-video_name ='" . $value->txtVideoOriginalName . '.' . $value->varVideoExtension . "'  id='video_" . $value->id . "' data-video_type='normal' data-video_source='" . Config::get('Constant.CDN_PATH') . '/assets/videos/' . $value->varVideoName . '.' . $value->varVideoExtension . "?autoplay=1'>
												<div class='thumbnail_container'>
													<div class='thumbnail' id='media_image_" . $value->id . "'>
														<img src='" . $this->_APP_URL . "resources/images/video_thumb_icon.png' />
													</div>
												</div>
												<a class='right_check' href='javascript:void(0)' ><i class=''></i></a>
												<div class='video_overflow'>
													<a title='" . $value->txtVideoOriginalName . '.' . $value->varVideoExtension . "' href='" . $videoUrl . "?autoplay=1' title='" . $value->varVideoName . "' class='link icns_set' data-fancybox>
														<span class='fa fa-play'></span>
													</a>
													<button title='Please select video and click on Insert Media button' class='icns_set' onclick=\"MediaManager.selectVideo('" . $value->id . "')\">
													<span class='fa fa-hand-pointer-o'></span>
													</button>
												</div>
											</div>";
                        }
                    }
                }
                $vidoeHtml .= '</div><div class="clearfix"></div>';
                $vidoeHtml .= '<div class="clearfix"></div></div>';
            } else {
                $vidoeHtml .= '<div class="portlet light"><h3>Videos are not available</h3></div>';
            }
            $response = $vidoeHtml;
        }
        echo $response;
        exit;
    }

    public function user_uploaded_docs()
    {
        $response = array();
        $docsHtml = '';
        if (Request::get('userid')) {
            $user_id = Request::get('userid');
            $docName = Request::get('docName');
            $limit = 50;
            $page = 1;
            $filterArr = array();
            if ($docName != "") {
                $filterArr['docName'] = $docName;
            }
            $documentObj = Document::getDocuments($limit, $page, 0, $filterArr);
            $Doc_html = $this->getDocHtml($documentObj, 'Documents', $filterArr, $limit);
            $response['Doc_html'] = $Doc_html;
            $response['docCount'] = count($documentObj);
        }
        //echo $response;
        echo json_encode($response);
        exit;
    }

    public function folder_uploaded_docs()
    {
        $response = array();
        $docsHtml = '';
        if (Request::get('userid')) {
            $user_id = Request::get('userid');
            $docName = Request::get('docName');
            $limit = 50;
            $page = 1;
            $filterArr = array();
            if ($docName != "") {
                $filterArr['docName'] = $docName;
            }
            $documentObj = Document::getDocuments($limit, $page, 0, $filterArr);
            $Doc_html = $this->getFolderDocHtml($documentObj, 'Documents', $filterArr, $limit);
            $response['Doc_html'] = $Doc_html;
            $response['docCount'] = count($documentObj);
        }
        //echo $response;
        echo json_encode($response);
        exit;
    }

    public function getImageDetails()
    {
        $image_id = Request::get('image_id');
        $folder_id = Request::get('folder_id');

        $imageObj = Image::getRecordById($image_id);
        $folderObj = Image::getFolderName($folder_id);
        if ($imageObj->varfolder == 'folder') {
            $mimeType = mime_content_type(public_path('/assets/images/' . $folderObj->foldername) . '/' . $imageObj->txtImageName . '.' . $imageObj->varImageExtension);
            $dimensionArr = getimagesize(public_path('/assets/images/' . $folderObj->foldername) . '/' . $imageObj->txtImageName . '.' . $imageObj->varImageExtension);

            $dimension = '';
            if (isset($dimensionArr[0]) && !empty($dimensionArr[0])) {
                $dimension = $dimensionArr[0] . ' * ' . $dimensionArr[1];
            }

            $file = filesize(public_path('/assets/images/' . $folderObj->foldername) . '/' . $imageObj->txtImageName . '.' . $imageObj->varImageExtension);
            $fileSize = Self::formatSizeUnits($file);
        } else {
            $mimeType = mime_content_type(public_path('/assets/images') . '/' . $imageObj->txtImageName . '.' . $imageObj->varImageExtension);
            $dimensionArr = getimagesize(public_path('/assets/images') . '/' . $imageObj->txtImageName . '.' . $imageObj->varImageExtension);

            $dimension = '';
            if (isset($dimensionArr[0]) && !empty($dimensionArr[0])) {
                $dimension = $dimensionArr[0] . ' * ' . $dimensionArr[1];
            }

            $file = filesize(public_path('/assets/images') . '/' . $imageObj->txtImageName . '.' . $imageObj->varImageExtension);
            $fileSize = Self::formatSizeUnits($file);
        }

        $html = view('powerpanel.media_manager.image_details', ['image_id' => $image_id, 'imageObj' => $imageObj, 'mimeType' => $mimeType, 'dimension' => $dimension, 'fileSize' => $fileSize])->render();
        return $html;
    }

    public function cropImage()
    {

        $image_id = Request::get('image_id');
        $folder_id = Request::get('folder_id');
        $imageObj = Image::getRecordById($image_id);
        $folderObj = Image::getFolderName($folder_id);

        if ($imageObj->varfolder == 'folder') {
            $imageURL = Config::get('Constant.CDN_PATH') . '/assets/images/' . $folderObj->foldername . '/' . $imageObj->txtImageName . '.' . $imageObj->varImageExtension;
        } else {
            $imageURL = Config::get('Constant.CDN_PATH') . '/assets/images' . '/' . $imageObj->txtImageName . '.' . $imageObj->varImageExtension;
        }
        $recommadeImageSizeArr = array();
        if (Config::has('Constant.RECOMMANDED_IMAGE_SIZES')) {
            $recommadeImageSize = Config::get('Constant.RECOMMANDED_IMAGE_SIZES');
            $recommadeImageSizeArr = explode(',', $recommadeImageSize);
        }
        $imageCropperView = view('powerpanel.media_manager.image_cropper', ['imageURL' => $imageURL, 'imageObj' => $imageObj, 'recommadeImageSizeArr' => $recommadeImageSizeArr])->render();

        return $imageCropperView;
    }

    public function saveCroppedImage()
    {

        $response = false;
        $image = Request::get('image');
        $image_id = Request::get('image_id');
        $overwrite = Request::get('overwrite');
        $imageObj = Image::getRecordById($image_id);

        if (!empty($imageObj)) {
            $folderObj = Image::getFolderName(Request::get('image_folderid'));

            $extension = $imageObj->varImageExtension;

            if ($overwrite == 'true') {
                $name = $imageObj->txtImageName;
                if ($imageObj->varfolder == 'folder') {
                    $path = Config::get('Constant.LOCAL_CDN_PATH') . '/assets/images/' . $folderObj->foldername . '/' . $name . '.' . $extension;
                } else {
                    $path = Config::get('Constant.LOCAL_CDN_PATH') . '/assets/images/' . $name . '.' . $extension;
                }
                $saved = InterventionImage::make($image)->save($path);
                $converted = ImageConvertor::convertImageToWebP($name, $extension, false, $imageObj->varfolder, $folderObj->foldername);
                $response = $imageObj->id;
            } else {
                $timestamp = date('YmdHis');
                $name = $imageObj->txtImgOriginalName . '-' . $timestamp;
                if ($imageObj->varfolder == 'folder') {
                    $path = Config::get('Constant.LOCAL_CDN_PATH') . '/assets/images/' . $folderObj->foldername . '/' . $name . '.' . $extension;
                } else {
                    $path = Config::get('Constant.LOCAL_CDN_PATH') . '/assets/images/' . $name . '.' . $extension;
                }
                $saved = InterventionImage::make($image)->save($path);
//                $converted = ImageConvertor::convertImageToWebP($name, $extension,false,$imageObj->varfolder,$folderObj->foldername);
                if (!empty($saved)) {
                    $imageArr = array();
                    $imageArr['fkIntUserId'] = Auth::user()->id;
                    $imageArr['txtImageName'] = $name;
                    $imageArr['txtImgOriginalName'] = $imageObj->txtImgOriginalName;
                    $imageArr['varImageExtension'] = $extension;
                    $imageArr['fk_folder'] = $imageObj->fk_folder;
                    $imageArr['varfolder'] = $imageObj->varfolder;
                    $imageArr['varTitle'] = $imageObj->txtImgOriginalName;
                    $imageArr['varAltText'] = $imageObj->txtImgOriginalName;
                    $imageArr['txtCaption'] = $imageObj->txtImgOriginalName;
                    $imageArr['created_at'] = date('Y-m-d H:i:s');
                    $response = CommonModel::addRecord($imageArr, '\\App\\Image');
                }
            }
        }
        return $response;
    }

    public function saveImageDetails()
    {
        $updateStatus = false;
        $imageData = Request::all();

        if (!empty($imageData)) {
            $imageFields = array();

            $imageFields['varTitle'] = $imageData['image_title'];
            $imageFields['varAltText'] = $imageData['image_caption'];
            $imageFields['txtCaption'] = $imageData['image_alt'];

            $updateStatus = Image::where('id', $imageData['image_id'])->update($imageFields);
        }

        return json_encode($updateStatus);
    }

    public function user_uploaded_audios()
    {
        $response = array();
        $audiosHtml = '';
        if (Request::get('userid')) {
            $user_id = Request::get('userid');
            $audioName = Request::get('audioName');
            $limit = 50;
            $page = 1;
            $filterArr = array();
            if ($audioName != "") {
                $filterArr['audioName'] = $audioName;
            }
            $audioumentObj = Audio::getAudios($limit, $page, 0, $filterArr);
            $Doc_html = $this->getAudioHtml($audioumentObj, 'Audios', $filterArr, $limit);
            $response['Doc_html'] = $Doc_html;
            $response['audioCount'] = count($audioumentObj);
        }
        //echo $response;
        echo json_encode($response);
        exit;
    }

    public function folder_uploaded_audios()
    {
        $response = array();
        $audiosHtml = '';
        if (Request::get('userid')) {
            $user_id = Request::get('userid');
            $audioName = Request::get('audioName');
            $limit = 50;
            $page = 1;
            $filterArr = array();
            if ($audioName != "") {
                $filterArr['audioName'] = $audioName;
            }
            $audioumentObj = Audio::getAudios($limit, $page, 0, $filterArr);
            $Doc_html = $this->getFolderAudioHtml($audioumentObj, 'Audios', $filterArr, $limit);
            $response['Doc_html'] = $Doc_html;
            $response['audioCount'] = count($audioumentObj);
        }
        //echo $response;
        echo json_encode($response);
        exit;
    }

    public function remove_image()
    {
        $response = false;
        if (Request::get('image_id')) {
            $whereCondition = ['id' => Request::get('image_id')];
            $updateImageFieldsArr = [];
            $updateImageFieldsArr['chrPublish'] = 'N';
            $updateImageFieldsArr['chrDelete'] = 'Y';
            $response = CommonModel::updateRecords($whereCondition, $updateImageFieldsArr, false, '\\App\\Image');
        }
        echo $response;
        exit;
    }

    public function updateDocTitle()
    {
        $response = false;
        if (Request::get('id')) {
            $whereCondition = ['id' => Request::get('id')];
            $updateImageFieldsArr = [];
            $updateImageFieldsArr['txtDocumentName'] = Request::get('gettitle');
            $response = CommonModel::updateRecords($whereCondition, $updateImageFieldsArr, false, '\\App\\Document');
        }
        echo json_encode($response);
        exit;
//                echo $response;
        //                exit;
    }

    public function updateAudioTitle()
    {
        $response = false;
        if (Request::get('id')) {
            $whereCondition = ['id' => Request::get('id')];
            $updateImageFieldsArr = [];
            $updateImageFieldsArr['txtAudioName'] = Request::get('gettitle');
            $response = CommonModel::updateRecords($whereCondition, $updateImageFieldsArr, false, '\\App\\Audio');
        }
        echo json_encode($response);
        exit;
    }

    public function remove_multiple_image()
    {
        $response = false;
        if (Request::get('idArr')) {
            if (Request::get('identity') && Request::get('identity') == "trash") {
                $files = array();
                if ($this->BUCKET_ENABLED) {
                    $filePath = $this->S3_MEDIA_BUCKET_PATH . '/';
                } else {
                    $filePath = Config::get('Constant.LOCAL_CDN_PATH') . '/assets/images/';
                }

                $fileDetails = Image::select(['txtImageName', 'varImageExtension'])->whereIn('id', Request::get('idArr'))->get();
                if (!empty($fileDetails)) {
                    foreach ($fileDetails as $file) {
                        if ($file->txtImageName != "" && $file->varImageExtension != "") {
                            $fileName = $file->txtImageName . '.' . $file->varImageExtension;
                            array_push($files, $fileName);
                        }
                    }
                }
                DB::statement('SET FOREIGN_KEY_CHECKS=0');
                $response = Image::whereIn('id', Request::get('idArr'))->delete();
                DB::statement('SET FOREIGN_KEY_CHECKS=1');
                if ($response) {
                    $this->removeFiles($filePath, $files);
                }
            } else {
                $response = Image::whereIn('id', Request::get('idArr'))->update(['chrPublish' => 'N', 'chrDelete' => 'Y']);
            }
        }
        echo $response;
        exit;
    }

    public function remove_multiple_documents()
    {
        $response = false;
        if (Request::get('idArr')) {
            if (Request::get('identity') && Request::get('identity') == "trash") {
                $files = array();
                if ($this->BUCKET_ENABLED) {
                    $filePath = $this->S3_MEDIA_BUCKET_DOCUMENT_PATH . '/';
                } else {
                    $filePath = Config::get('Constant.LOCAL_CDN_PATH') . '/documents/';
                }

                $documentsDetails = Document::select(['txtSrcDocumentName', 'varDocumentExtension'])->whereIn('id', Request::get('idArr'))->get();
                if (!empty($documentsDetails)) {
                    foreach ($documentsDetails as $document) {
                        if ($document->txtSrcDocumentName != "" && $document->varDocumentExtension != "") {
                            $docName = $document->txtSrcDocumentName . '.' . $document->varDocumentExtension;
                            array_push($files, $docName);
                        }
                    }
                }
                DB::statement('SET FOREIGN_KEY_CHECKS=0');
                $response = Document::whereIn('id', Request::get('idArr'))->delete();
                DB::statement('SET FOREIGN_KEY_CHECKS=1');
                if ($response) {
                    $this->removeFiles($filePath, $files);
                }
            } else {
                $response = Document::whereIn('id', Request::get('idArr'))->update(['chrPublish' => 'N', 'chrDelete' => 'Y']);
            }
        }
        echo $response;
        exit;
    }

    public function remove_multiple_audios()
    {
        $response = false;
        if (Request::get('idArr')) {
            if (Request::get('identity') && Request::get('identity') == "trash") {
                $files = array();
                if ($this->BUCKET_ENABLED) {
                    $filePath = $this->S3_MEDIA_BUCKET_AUDIO_PATH . '/';
                } else {
                    $filePath = Config::get('Constant.LOCAL_CDN_PATH') . '/audios/';
                }

                $audiosDetails = Audio::select(['txtSrcAudioName', 'varAudioExtension'])->whereIn('id', Request::get('idArr'))->get();
                if (!empty($audiosDetails)) {
                    foreach ($audiosDetails as $audio) {
                        if ($audio->txtSrcAudioName != "" && $audio->varAudioExtension != "") {
                            $docName = $audio->txtSrcAudioName . '.' . $audio->varAudioExtension;
                            array_push($files, $docName);
                        }
                    }
                }
                DB::statement('SET FOREIGN_KEY_CHECKS=0');
                $response = Audio::whereIn('id', Request::get('idArr'))->delete();
                DB::statement('SET FOREIGN_KEY_CHECKS=1');
                if ($response) {
                    $this->removeFiles($filePath, $files);
                }
            } else {
                $response = Audio::whereIn('id', Request::get('idArr'))->update(['chrPublish' => 'N', 'chrDelete' => 'Y']);
            }
        }
        echo $response;
        exit;
    }

    public function get_recent_uploaded_images()
    {
        $response = false;

        if (Request::get('user_id')) {

            $user_id = Request::get('user_id');
            $recently_uploaded = Image::getRecentUploadedImages();

            $Image_html = '<div class="title_section">';
            $Image_html .= '<h2>Recently Uploaded</h2>';
            $Image_html .= '<div class="pull-right">';

            if ($recently_uploaded->count() > 0) {
                $Image_html .= '<a class="btn btn-green-drake" id="insert_image" onclick="MediaManager.insertMedia();" href="javascript:void(0);" style="padding:4px 12px">Insert Media</a>&nbsp;';

                $Image_html .= '<a class="btn btn-green-drake" id="delete_image" style="padding:4px 12px;margin-right:10px;" onclick=\'MediaManager.openConfirmBox("Image",false,"recent")\' href="javascript:void(0);" >Delete</a>';
            }
            $Image_html .= '</div></div><div class="clearfix"></div>';

            if ($recently_uploaded->count() > 0) {
                $Image_html .= '<div class="portlet light">';
                $Image_html .= '<p id="note"></p>';
                $Image_html .= '<div class="scroller gallery">';
                $Image_html .= '<div id="recent_upload_images">';
                foreach ($recently_uploaded as $key => $value) {
                    $folderdata = Image::getFolderName($value->fk_folder);
                    if ($this->BUCKET_ENABLED) {
                        $img_path = $this->S3_MEDIA_BUCKET_PATH . '/' . $value->txtImageName . '.' . $value->varImageExtension;
                    } else {
                        if (!empty($folderdata) && isset($value->varfolder) && $value->varfolder == 'folder') {
                            $img_path = Config::get('Constant.LOCAL_CDN_PATH') . '/assets/images/' . $folderdata->foldername . '/' . $value->txtImageName . '.' . $value->varImageExtension;
                        } else {
                            $img_path = Config::get('Constant.LOCAL_CDN_PATH') . '/assets/images/' . $value->txtImageName . '.' . $value->varImageExtension;
                        }
                    }
                    if (!empty($folderdata) && isset($value->varfolder) && $value->varfolder == 'folder') {
                        $imageurl = Config::get('Constant.CDN_PATH') . '/assets/images/' . $folderdata->foldername . '/' . $value->txtImageName . '.' . $value->varImageExtension;
                    } else {
                        $imageurl = resize_image::resize($value->id, 195, 135);
                    }

                    if ($this->filePathExist($img_path)) {
                        $Image_html .= "<div class='img-box contains_thumb' id='media_" . $value->id . "'>
																				 <div class='thumbnail_container'>
																							<div class='thumbnail' id='media_image_" . $value->id . "'>
																						 <a  title='" . $value->txtImgOriginalName . "' href='javascript:void(0);' onclick=\"MediaManager.selectRecentUploadImage('" . $value->id . "')\" >
																								 <img alt='" . $value->txtImgOriginalName . "' src='" . $imageurl . "'>
																								 <span class='icon-check' aria-hidden='true'></span>
																																																									</a>
																									</div>
																								</div>
																								<a class='right_check' href='javascript:void(0)' ><i class=''></i></a>";
                        $Image_html .= "<div class='img-btns'>";
                        $Image_html .= "<a href='javascript:;' title='Image Detail' onclick=\"MediaManager.getImageDetails('" . $value->id . "');\"><span class='icon icon-info'></span></a>";

                        if ($value->varImageExtension != "svg") {
                            $Image_html .= "<a href='javascript:;' title='Crop Image' onclick=\"MediaManager.cropImage('" . $value->id . "');\"><span class='icon-crop'></span></a>";
                        }

                        $Image_html .= "</div></div>";
                    }
                }

                $Image_html .= '</div></div><div class="clearfix"></div><div class="clearfix"></div>';
            } else {

                $Image_html .= '<div class="portlet light"><h3>Images are not available</h3></div>';
            }
        }

        $response = $Image_html;
        echo $response;
        exit;
    }

    public function get_trash_images()
    {
        $response = false;
        if (Request::get('user_id')) {
            $user_id = Request::get('user_id');
            $trash_images = Image::getTrashedImages();
            $Image_html = '<div class="title_section">
						<h2>Trashed Images</h2>';
            $Image_html .= '<div class="pull-right trashed-rs"><div class="trashed-rs-inner">';
            if ($trash_images->count() > 0) {
                $Image_html .= '<a class="btn btn-green-drake" id="restore_images" style="padding:4px 12px;margin-right:10px;" onclick=\'MediaManager.openRestoreConfirmBox("Image",true);\' href="javascript:void(0);" >Restore</a>';
                $Image_html .= '<a class="btn btn-green-drake" id="permanent_delete_images" style="padding:4px 12px;margin-right:10px;" onclick=\'MediaManager.openConfirmBox("Image",true);\' href="javascript:void(0);" >Delete Permanently</a>';
                $Image_html .= '<a class="btn btn-green-drake" id="empty_trash_images" style="padding:4px 12px;margin-right:10px;" onclick=\'MediaManager.emptyTrash("Image");\' href="javascript:void(0);" >Empty Trash</a>';
            }
            $Image_html .= '</div></div><div class="clearfix"></div></div>';
            if ($trash_images->count() > 0) {
                $Image_html .= '<div class="portlet light">';
                $Image_html .= '<div class="scroller gallery">';
                $Image_html .= '<div id="append_image">';
                foreach ($trash_images as $key => $value) {
                    $folderdata = Image::getFolderName($value->fk_folder);
                    if ($this->BUCKET_ENABLED) {
                        $img_path = $this->S3_MEDIA_BUCKET_PATH . '/' . $value->txtImageName . '.' . $value->varImageExtension;
                    } else {
                        if (!empty($folderdata) && $value->varfolder == 'folder') {
                            $img_path = Config::get('Constant.LOCAL_CDN_PATH') . '/assets/images/' . $folderdata->foldername . '/' . $value->txtImageName . '.' . $value->varImageExtension;
                        } else {
                            $img_path = Config::get('Constant.LOCAL_CDN_PATH') . '/assets/images/' . $value->txtImageName . '.' . $value->varImageExtension;
                        }
                    }
                    if (!empty($folderdata) && $value->varfolder == 'folder') {
                        $imageurl = Config::get('Constant.CDN_PATH') . '/assets/images/' . $folderdata->foldername . '/' . $value->txtImageName . '.' . $value->varImageExtension;
                    } else {
                        $imageurl = resize_image::resize($value->id, 195, 135);
                    }
                    if ($this->filePathExist($img_path)) {
                        $Image_html .= "<div class='img-box contains_thumb' id='media_" . $value->id . "'>
																	<div class='thumbnail_container'>
																		<div class='thumbnail' id='media_image_" . $value->id . "'>
																			<a  title='" . $value->txtImgOriginalName . "' href='javascript:void(0);' onclick=\"MediaManager.selectImage('" . $value->id . "')\" >
																				<img alt='" . $value->txtImgOriginalName . "' src='" . $imageurl . "'>
																				<span class='icon-check' aria-hidden='true'></span>
																			</a>
																		</div>
																	</div>
																	<a class='right_check' href='javascript:void(0)' ><i class=''></i></a>
																</div>";
                    }
                }
                $Image_html .= '</div></div><div class="clearfix"></div>';
            } else {
                $Image_html .= '<div class="portlet light"><h3>Images are not available</h3></div>';
            }
        }

        $response = $Image_html;
        echo $response;
        exit;
    }

    public function get_trash_videos()
    {
        $response = false;
        if (Request::get('user_id')) {
            $user_id = Request::get('user_id');
            $videos = Video::getTrashedVideos();
            $vidoeHtml = '<div class="title_section">
						<h2>Trashed Videos</h2>';
            $vidoeHtml .= '<div class="pull-right trashed-rs"><div class="trashed-rs-inner">';
            if ($videos->count() > 0) {
                $vidoeHtml .= '<a class="btn btn-green-drake" id="restore_videos" style="padding:4px 12px;margin-right:10px;" onclick=\'MediaManager.openRestoreConfirmBox("Video",true);\' href="javascript:void(0);" >Restore</a>';
                $vidoeHtml .= '<a class="btn btn-green-drake" id="permanent_delete_videos" style="padding:4px 12px;margin-right:10px;" onclick=\'MediaManager.openConfirmBox("video",true);\' href="javascript:void(0);" >Delete Permanently</a>';
                $vidoeHtml .= '<a class="btn btn-green-drake" id="empty_trash_videos" style="padding:4px 12px;margin-right:10px;" onclick=\'MediaManager.emptyTrash("Video");\' href="javascript:void(0);" >Empty Trash</a>';
            }
            $vidoeHtml .= '</div></div><div class="clearfix"></div></div>';
            if ($videos->count() > 0) {
                $vidoeHtml .= '<div class="portlet light">
									<div class="scroller gallery">
										<div id="append_user_image">';
                foreach ($videos as $key => $value) {
                    if (isset($value->youtubeId) && !empty($value->youtubeId)) {
                        $vidoeHtml .= "<div class='img-box video_thumb contains_thumb' data-video_name='" . $value->youtubeId . "' id='video_" . $value->id . "'>
												<div class='thumbnail_container'>
													<div class='thumbnail' id='media_image_" . $value->id . "'>
														<img src='http://img.youtube.com/vi/" . $value->youtubeId . "/default.jpg' />
													</div>
												</div>
												<a class='right_check' href='javascript:void(0)' ><i class=''></i></a>
												<div class='video_overflow'>
													<a title='" . $value->youtubeId . "' href='http://www.youtube.com/embed/" . $value->youtubeId . "?autoplay=1' class='link fancybox fancybox.iframe icns_set' data-fancybox-group='gallery'><span class='fa fa-play'></span></a>
													<button title='Please select video and click on Insert Media button' class='icns_set' onclick=\"MediaManager.selectVideo('" . $value->id . "')\">
													<span class='fa fa-hand-pointer-o'></span>
													</button>
												</div>
											</div>";
                    } else {
                        if ($this->BUCKET_ENABLED) {
                            $video_path = $this->S3_MEDIA_BUCKET_VIDEO_PATH . '/' . $value->varVideoName . '.' . $value->varVideoExtension;
                            $videoUrl = $this->_APP_URL . $this->S3_MEDIA_BUCKET_VIDEO_PATH . '/' . $value->varVideoName . '.' . $value->varVideoExtension;
                        } else {
                            $video_path = Config::get('Constant.LOCAL_CDN_PATH') . '/assets/videos/' . $value->varVideoName . '.' . $value->varVideoExtension;
                            $videoUrl = Config::get('Constant.CDN_PATH') . '/assets/videos/' . $value->varVideoName . '.' . $value->varVideoExtension;
                        }

                        if ($this->filePathExist($video_path)) {
                            $vidoeHtml .= "<div class='img-box video_thumb contains_thumb' data-video_name ='" . $value->txtVideoOriginalName . '.' . $value->varVideoExtension . "'  id='video_" . $value->id . "'>
												<div class='thumbnail_container'>
													<div class='thumbnail' id='media_image_" . $value->id . "'>
														<img src='" . $this->_APP_URL . "resources/images/video_thumb_icon.png' />
													</div>
												</div>
												<a class='right_check' href='javascript:void(0)' ><i class=''></i></a>
												<div class='video_overflow'>
													<a title='" . $value->txtVideoOriginalName . '.' . $value->varVideoExtension . "' href='" . $videoUrl . "?autoplay=1' title='" . $value->varVideoName . "' class='link fancybox fancybox.iframe icns_set' data-fancybox-group='gallery'>
														<span class='fa fa-play'></span>
													</a>
													<button title='Please select video and click on Insert Media button' class='icns_set' onclick=\"MediaManager.selectVideo('" . $value->id . "')\">
													<span class='fa fa-hand-pointer-o'></span>
													</button>
												</div>
											</div>";
                        }
                    }
                }
                $vidoeHtml .= '</div><div class="clearfix"></div>';
                $vidoeHtml .= '<div class="clearfix"></div></div>';
            } else {
                $vidoeHtml .= '<div class="portlet light"><h3>Videos are not available</h3></div>';
            }
        }

        $response = $vidoeHtml;
        echo $response;
        exit;
    }

    public function get_trash_documents()
    {
        $response = false;
        if (Request::get('user_id')) {
            $user_id = Request::get('user_id');
            $trash_documents = Document::getTrashedDocuments();

            $html = '<div class="title_section">
						<h2>Trashed Document(s)</h2>
						<div class="pull-right trashed-rs"><div class="trashed-rs-inner">';
            if (count($trash_documents) > 0) {
                $html .= '<a class="btn btn-green-drake" id="restore_images" style="padding:4px 12px;margin-right:10px;" onclick=\'MediaManager.openRestoreConfirmBox("Document",true);\' href="javascript:void(0);" >Restore</a>';
                $html .= '<a class="btn btn-green-drake" id="permanent_delete_document" style="padding:4px 12px;margin-right:10px;" onclick=\'MediaManager.openConfirmBox("document",true);\' href="javascript:void(0);" >Delete Permanently</a>';
                $html .= '<a class="btn btn-green-drake" id="empty_trash_document" style="padding:4px 12px;margin-right:10px;" onclick=\'MediaManager.emptyTrash("Document");\' href="javascript:void(0);" >Empty Trash</a>';
            }
            $html .= '</div></div><div class="clearfix"></div></div>';
            if (count($trash_documents) > 0) {
                $html .= '<div class="portlet light">';
                $html .= '<div id="append_image">';
                foreach ($trash_documents as $key => $value) {
                    $folderdata = Document::getFolderName($value->fk_folder);
                    if ($this->BUCKET_ENABLED) {
                        $doc_path = $this->S3_MEDIA_BUCKET_DOCUMENT_PATH . '/' . $value->txtSrcDocumentName . '.' . $value->varDocumentExtension;
                    } else {
                        if (!empty($folderdata) && $value->varfolder == 'folder') {
                            $doc_path = Config::get('Constant.LOCAL_CDN_PATH') . '/documents/' . $folderdata->foldername . '/' . $value->txtSrcDocumentName . '.' . $value->varDocumentExtension;
                        } else {
                            $doc_path = Config::get('Constant.LOCAL_CDN_PATH') . '/documents/' . $value->txtSrcDocumentName . '.' . $value->varDocumentExtension;
                        }
                    }

                    if ($this->filePathExist($doc_path)) {
                        $html .= "<div class='img-box contains_thumb' id='document_" . $value->id . "'>
																	<div class='thumbnail_container'>
																		<div class='thumbnail'>
																			<a  title='" . $value->txtDocumentName . "' href='javascript:void(0);' onclick=\"MediaManager.selectDocument('" . $value->id . "')\">";
                        if ($value->varDocumentExtension == "pdf") {
                            $html .= "<img alt='" . $value->txtDocumentName . "' src='" . $this->_APP_URL . "assets/images/documents_logo/pdf.png'>";
                        } elseif ($value->varDocumentExtension == "xls" || $value->varDocumentExtension == "xlsx" || $value->varDocumentExtension == "xlsm") {
                            $html .= "<img alt='" . $value->txtDocumentName . "' src='" . $this->_APP_URL . "assets/images/documents_logo/xls.png'>";
                        } elseif ($value->varDocumentExtension == "docx" || $value->varDocumentExtension == "doc") {
                            $html .= "<img alt='" . $value->txtDocumentName . "' src='" . $this->_APP_URL . "assets/images/documents_logo/doc.png'>";
                        } elseif ($value->varDocumentExtension == "ppt") {
                            $html .= "<img alt='" . $value->txtDocumentName . "' src='" . $this->_APP_URL . "assets/images/documents_logo/ppt.png'>";
                        } elseif ($value->varDocumentExtension == "txt") {
                            $html .= "<img alt='" . $value->txtDocumentName . "' src='" . $this->_APP_URL . "assets/images/documents_logo/txt.png'>";
                        } else {
                            $html .= "<img alt='" . $value->txtDocumentName . "' src='" . $this->_APP_URL . "assets/images/documents_logo/document_icon.png'>";
                        }

                        $html .= "<span class='icon-check' aria-hidden='true'></span>
																			</a>
																		</div>
																		</div>
																		<a class='right_check' href='javascript:void(0)' ><i class=''></i></a>
																</div>";
                    }
                }
                $html .= '</div><div class="clearfix"></div>';
            } else {
                $html .= '<div class="portlet light"><h3>Document(s) are not available</h3></div>';
            }
        }

        $response = $html;
        echo $response;
        exit;
    }

    public function get_trash_audios()
    {
        $response = false;
        if (Request::get('user_id')) {
            $user_id = Request::get('user_id');
            $trash_audios = Audio::getTrashedAudios();

            $html = '<div class="title_section">
						<h2>Trashed Audio(s)</h2>
						<div class="pull-right trashed-rs"><div class="trashed-rs-inner">';
            if (count($trash_audios) > 0) {
                $html .= '<a class="btn btn-green-drake" id="restore_images" style="padding:4px 12px;margin-right:10px;" onclick=\'MediaManager.openRestoreConfirmBox("Audio",true);\' href="javascript:void(0);" >Restore</a>';
                $html .= '<a class="btn btn-green-drake" id="permanent_delete_audio" style="padding:4px 12px;margin-right:10px;" onclick=\'MediaManager.openConfirmBox("audio",true);\' href="javascript:void(0);" >Delete Permanently</a>';
                $html .= '<a class="btn btn-green-drake" id="empty_trash_audio" style="padding:4px 12px;margin-right:10px;" onclick=\'MediaManager.emptyTrash("Audio");\' href="javascript:void(0);" >Empty Trash</a>';
            }
            $html .= '</div></div><div class="clearfix"></div></div>';
            if (count($trash_audios) > 0) {
                $html .= '<div class="portlet light">';
                $html .= '<div id="append_image">';
                foreach ($trash_audios as $key => $value) {

                    $folderdata = Audio::getFolderName($value->fk_folder);
                    if ($this->BUCKET_ENABLED) {
                        $doc_path = $this->S3_MEDIA_BUCKET_AUDIO_PATH . '/' . $value->txtSrcAudioName . '.' . $value->varAudioExtension;
                    } else {
                        if (!empty($folderdata) && $value->varfolder == 'folder') {
                            $doc_path = Config::get('Constant.LOCAL_CDN_PATH') . '/audios/' . $folderdata->foldername . '/' . $value->txtSrcAudioName . '.' . $value->varAudioExtension;
                        } else {
                            $doc_path = Config::get('Constant.LOCAL_CDN_PATH') . '/audios/' . $value->txtSrcAudioName . '.' . $value->varAudioExtension;
                        }
                    }

                    if ($this->filePathExist($doc_path)) {
                        $html .= "<div class='img-box contains_thumb' id='audio_" . $value->id . "'>
																	<div class='thumbnail_container'>
																		<div class='thumbnail'>
																			<a  title='" . $value->txtAudioName . "' href='javascript:void(0);' onclick=\"MediaManager.selectAudio('" . $value->id . "')\">";
                        $html .= "<audio id='myAudio_" . $value->id . "'><source src='" . $doc_path . "' type='audio/mpeg'></audio>";
                        $html .= '<input type="hidden" id="audioid" value="' . $value->id . '">';
                        if (strtolower($value->varAudioExtension) == "mp3") {
                            $html .= "<img alt='" . $value->txtAudioName . "' src='" . $this->_APP_URL . "assets/images/audios_logo/mp3.png'>";
                        } elseif (strtolower($value->varAudioExtension) == "mp4") {
                            $html .= "<img alt='" . $value->txtAudioName . "' src='" . $this->_APP_URL . "assets/images/audios_logo/mp4.png'>";
                        } elseif (strtolower($value->varAudioExtension) == "wav") {
                            $html .= "<img alt='" . $value->txtAudioName . "' src='" . $this->_APP_URL . "assets/images/audios_logo/wav.png'>";
                        } else {
                            $html .= "<img alt='" . $value->txtAudioName . "' src='" . $this->_APP_URL . "assets/images/audios_logo/mp3.png'>";
                        }

                        $html .= "<span class='icon-check' aria-hidden='true'></span>
																			</a>
																																																																												<div class='mp3_overlay'><span><a onclick=\"Playaudio('" . $value->id . "')\"><div id='audiohtml_" . $value->id . "'><i class='fa fa-play' title='Play'></i></div></a></span></div>
																		</div>
																		</div>
																		<a class='right_check' href='javascript:void(0)' ><i class=''></i></a>
																</div>";
                    }
                }
                $html .= '</div><div class="clearfix"></div>';
            } else {
                $html .= '<div class="portlet light"><h3>Audio(s) are not available</h3></div>';
            }
        }

        $response = $html;
        echo $response;
        exit;
    }

    public function empty_trash_image()
    {
        $response = false;
        if (Request::get('mediaType')) {
            if (Request::get('mediaType') == "Image") {
                $files = array();
                $ids = array();
                if ($this->BUCKET_ENABLED) {
                    $filePath = $this->S3_MEDIA_BUCKET_PATH . '/';
                } else {
                    $filePath = Config::get('Constant.LOCAL_CDN_PATH') . '/assets/images/';
                }

                $trashData = Image::getAllTrashedImagesIds();
                if (!empty($trashData)) {
                    foreach ($trashData as $file) {
                        array_push($ids, $file->id);
                        if ($file->txtImageName != "" && $file->varImageExtension != "") {
                            $fileName = $file->txtImageName . '.' . $file->varImageExtension;
                            array_push($files, $fileName);
                        }
                    }
                }
                if (!empty($ids)) {
                    DB::statement('SET FOREIGN_KEY_CHECKS=0');
                    $response = Image::whereIn('id', $ids)->delete();
                    DB::statement('SET FOREIGN_KEY_CHECKS=1');
                    if ($response) {
                        $this->removeFiles($filePath, $files);
                    }
                }
            }
        }
        echo $response;
        exit;
    }

    public function empty_trash_video()
    {
        $response = false;
        if (Request::get('mediaType')) {
            if (Request::get('mediaType') == "Video") {

                $files = array();
                $ids = array();
                if ($this->BUCKET_ENABLED) {
                    $filePath = $this->S3_MEDIA_BUCKET_GENERAL_PATH . '/';
                } else {
                    $filePath = Config::get('Constant.LOCAL_CDN_PATH') . '/assets/videos/';
                }

                $trashData = Video::getAllTrashedVideosIds();
                if (!empty($trashData)) {
                    foreach ($trashData as $file) {
                        array_push($ids, $file->id);
                        if ($file->varVideoName != "" && $file->varVideoExtension != "") {
                            $fileName = $file->varVideoName . '.' . $file->varVideoExtension;
                            array_push($files, $fileName);
                        }
                    }
                }

                if (!empty($ids)) {
                    DB::statement('SET FOREIGN_KEY_CHECKS=0');
                    $response = Video::whereIn('id', $ids)->delete();
                    DB::statement('SET FOREIGN_KEY_CHECKS=1');
                    if ($response) {
                        $this->removeFiles($filePath, $files);
                    }
                }
            }
        }
        echo $response;
        exit;
    }

    public function empty_trash_document()
    {
        $response = false;
        if (Request::get('mediaType')) {
            if (Request::get('mediaType') == "Document") {
                $files = array();
                $ids = array();
                if ($this->BUCKET_ENABLED) {
                    $filePath = $this->S3_MEDIA_BUCKET_DOCUMENT_PATH . '/';
                } else {
                    $filePath = Config::get('Constant.LOCAL_CDN_PATH') . '/documents/';
                }

                $trashData = Document::getAllTrashedDocumentsIds();
                if (!empty($trashData)) {
                    foreach ($trashData as $file) {
                        array_push($ids, $file->id);
                        if ($file->txtSrcDocumentName != "" && $file->varDocumentExtension != "") {
                            $fileName = $file->txtSrcDocumentName . '.' . $file->varDocumentExtension;
                            array_push($files, $fileName);
                        }
                    }
                }

                if (!empty($ids)) {
                    DB::statement('SET FOREIGN_KEY_CHECKS=0');
                    $response = Document::whereIn('id', $ids)->delete();
                    DB::statement('SET FOREIGN_KEY_CHECKS=1');
                    if ($response) {
                        $this->removeFiles($filePath, $files);
                    }
                }
            }
        }
        echo $response;
        exit;
    }

    public function empty_trash_audio()
    {
        $response = false;
        if (Request::get('mediaType')) {
            if (Request::get('mediaType') == "Audio") {
                $files = array();
                $ids = array();
                if ($this->BUCKET_ENABLED) {
                    $filePath = $this->S3_MEDIA_BUCKET_AUDIO_PATH . '/';
                } else {
                    $filePath = Config::get('Constant.LOCAL_CDN_PATH') . '/audios/';
                }

                $trashData = Audio::getAllTrashedAudiosIds();
                if (!empty($trashData)) {
                    foreach ($trashData as $file) {
                        array_push($ids, $file->id);
                        if ($file->txtSrcAudioName != "" && $file->varAudioExtension != "") {
                            $fileName = $file->txtSrcAudioName . '.' . $file->varAudioExtension;
                            array_push($files, $fileName);
                        }
                    }
                }

                if (!empty($ids)) {
                    DB::statement('SET FOREIGN_KEY_CHECKS=0');
                    $response = Audio::whereIn('id', $ids)->delete();
                    DB::statement('SET FOREIGN_KEY_CHECKS=1');
                    if ($response) {
                        $this->removeFiles($filePath, $files);
                    }
                }
            }
        }
        echo $response;
        exit;
    }

    public function insert_image_by_url()
    {
        $response = false;
        if (Request::get('url')) {

            if (!filter_var(Request::get('url'), FILTER_VALIDATE_URL) === false) {
                $filename = substr(Request::get('url'), strrpos(Request::get('url'), '/') + 1);

                if (!empty($filename)) {
                    $pathinfo = pathinfo(Request::get('url'));
                    if (isset($pathinfo['extension'])) {
                        if ($pathinfo['extension'] == "jpg" || $pathinfo['extension'] == "jpeg" || $pathinfo['extension'] == "png" || $pathinfo['extension'] == "gif") {
                            $timestamp = str_replace([' ', ':'], '-', Carbon::now()->toDateTimeString());
                            $name = $timestamp . '-' . self::clean($pathinfo['filename']);

                            file_put_contents(Config::get('Constant.LOCAL_CDN_PATH') . '/assets/images/' . $name . '.' . $pathinfo['extension'], file_get_contents(Request::get('url')));

                            $imageArr = array();
                            $imageArr['fkIntUserId'] = Auth::user()->id;
                            $imageArr['txtImageName'] = $name;
                            $imageArr['txtImgOriginalName'] = $pathinfo['filename'];
                            $imageArr['varImageExtension'] = $pathinfo['extension'];
                            $imageArr['chrIsUserUploaded'] = 'Y';
                            $imageArr['created_at'] = Carbon::now();
                            $imageID = CommonModel::addRecord($imageArr, '\\App\\Image');
                            if ($imageID) {

                                $image_data = Image::getImg($imageID);
                                $imagePath = Config::get('Constant.LOCAL_CDN_PATH') . '/assets/images/' . $image_data->txtImageName . '.' . $image_data->varImageExtension;
                                if ($this->filePathExist($imagePath)) {
                                    $response['image_id'] = $imageID;
                                } else {
                                    $response['error'] = 'Image not exists in source directory.';
                                }
                            } else {
                                $response['error'] = 'Image not inserted successfully.';
                            }
                        } else {
                            $response['error'] = 'Image is not valid';
                        }
                    } else {
                        $response['error'] = 'URL is not valid';
                    }
                } else {
                    $response['error'] = 'Please enter valid url.';
                }
            } else {
                $response['error'] = 'Please enter valid url';
            }
        }

        echo json_encode($response);
        exit;
    }

    public function insert_video_by_url()
    {
        $response = false;
        if (Request::get('url')) {

            if (!filter_var(Request::get('url'), FILTER_VALIDATE_URL) === false) {
                $mediaUrlType = Request::get('url_Type');
                if ($mediaUrlType == "vimeo") {
                    $vimeo_id = $this->vimeo_id_from_url(Request::get('url'));
                    if ($vimeo_id) {
                        $vimeo_information = $this->vimeo_infoby_id($vimeo_id);
                        if (!empty($vimeo_information)) {
                            $vimeoTitle = $vimeo_information['title'];
                            $user = Auth::user();
                            $videos = new Video;
                            $videos->fkIntUserId = $user->id;
                            $videos->varVideoName = $vimeoTitle;
                            $videos->txtVideoOriginalName = $vimeoTitle;
                            $videos->varMediaVideoUrlType = "vimeo";
                            $videos->vimeoId = $vimeo_id;
                            $videos->varExtVideoUrl = Request::get('url');
                            $videos->chrIsUserUploaded = 'Y';
                            $videos->save();
                        } else {
                            $response['error'] = 'Video is not available.';
                        }
                    } else {
                        $response['error'] = 'Please enter valid vimeo url';
                    }
                } else {
                    $youtube_id = $this->youtube_id_from_url(Request::get('url'));
                    if ($youtube_id) {
                        $apiURL = 'https://www.youtube.com/oembed?url=http://www.youtube.com/watch?v=' . $youtube_id . '&format=json';
                        # curl options
                        $options = array(
                            CURLOPT_URL => $apiURL,
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_BINARYTRANSFER => true,
                            CURLOPT_SSL_VERIFYPEER => false,
                            CURLOPT_TIMEOUT => 5);
                        # connect api server through cURL
                        $ch = curl_init();
                        curl_setopt_array($ch, $options);
                        # execute cURL
                        $json = curl_exec($ch) or die(curl_error($ch));
                        # close cURL connect
                        curl_close($ch);
                        # decode json encoded data
                        if ($data = json_decode($json)) {
                            $youTubeTitle = $data->title;
                        }
                        /* Save file wherever you want */
                        $user = Auth::user();
                        $videos = new Video;

                        $videos->fkIntUserId = $user->id;
                        $videos->varVideoName = !empty($youTubeTitle) ? $youTubeTitle : '';
                        $videos->txtVideoOriginalName = !empty($youTubeTitle) ? $youTubeTitle : '';
                        $videos->youtubeId = $youtube_id;
                        $videos->varMediaVideoUrlType = "youtube";
                        $videos->varExtVideoUrl = Request::get('url');
                        $videos->chrIsUserUploaded = 'Y';
                        $videos->save();
                        if ($videos->id) {
                            $videoObj = Video::publish()->deleted()->checkRecordId($videos->id)->first();
                            if (isset($videoObj->youtubeId)) {
                                $response['html'] = "<div class='img-box video_thumb contains_thumb'>
														<div class='thumbnail_container'>
																<div class='thumbnail'>
																		<img src='http://img.youtube.com/vi/" . $videoObj->youtubeId . "/default.jpg' />
																</div>
														</div>
														<div class='video_overflow'>
																<a title=" . $videoObj->varVideoName . " href='http://www.youtube.com/embed/" . $videoObj->youtubeId . "?autoplay=1' class='link fancybox fancybox.iframe icns_set' data-fancybox-group='gallery'>
																		<span class='fa fa-play'></span>
																</a>
																<button title='Please select video and click on Insert Media button' class='icns_set' onclick='MediaManager.selectVideo('2')'>
																<span class='fa fa-hand-pointer-o'></span>
																</button>
														</div>
												</div>";
                            }
                        } else {
                            $response['error'] = 'Video is not available.';
                        }
                    } else {
                        $response['error'] = 'Please enter valid youtube url';
                    }
                }
            } else {
                $response['error'] = 'Please enter valid url';
            }
        }
        echo json_encode($response);
        exit;
    }

    public function remove_multiple_videos()
    {
        $response = false;
        if (Request::get('idArr')) {
            if (Request::get('identity') && Request::get('identity') == "trash") {
                $files = array();
                $filePath = Config::get('Constant.LOCAL_CDN_PATH') . '/assets/videos/';
                $fileDetails = Video::select(['varVideoName', 'varVideoExtension'])->where('chrPublish', 'N')->where('chrDelete', 'Y')->whereIn('id', Request::get('idArr'))->get();
                if (!empty($fileDetails)) {
                    foreach ($fileDetails as $file) {
                        if ($file->varVideoName != "" && $file->varVideoExtension != "") {
                            $fileName = $file->varVideoName . '.' . $file->varVideoExtension;
                            array_push($files, $fileName);
                        }
                    }
                }
                $response = Video::whereIn('id', Request::get('idArr'))->where('chrPublish', 'N')->where('chrDelete', 'Y')->delete();
                if ($response) {
                    $this->removeFiles($filePath, $files);
                }
            } else {
                $response = Video::whereIn('id', Request::get('idArr'))->update(['chrPublish' => 'N', 'chrDelete' => 'Y']);
            }
        }
        echo $response;
        exit;
    }

    public function youtube_id_from_url($url)
    {

        $pattern = '%^# Match any youtube URL
												(?:https?://)?  # Optional scheme. Either http or https
												(?:www\.)?      # Optional www subdomain
												(?:             # Group host alternatives
													youtu\.be/    # Either youtu.be,
												| youtube\.com  # or youtube.com
													(?:           # Group path alternatives
														/embed/     # Either /embed/
													| /v/         # or /v/
													| /watch\?v=  # or /watch\?v=
													)             # End path alternatives.
												)               # End host alternatives.
												([\w-]{10,12})  # Allow 10-12 for 11 char youtube id.
												$%x';

        $result = preg_match($pattern, $url, $matches);
        if ($result) {
            return $matches[1];
        }
        return false;
    }

    public function vimeo_id_from_url($url)
    {
        $regs = array();
        $id = false;
        if (preg_match('%^https?:\/\/(?:www\.|player\.)?vimeo.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|album\/(\d+)\/video\/|video\/|)(\d+)(?:$|\/|\?)(?:[?]?.*)$%im', $url, $regs)) {
            $id = $regs[3];
            return $id;
        }
        return $id;
    }

    public function vimeo_infoby_id($vimeo_id)
    {
        //forming API url
        $url = "http://vimeo.com/api/v2/video/" . $vimeo_id . ".json";
        //curl request
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $curlData = curl_exec($curl);
        curl_close($curl);

        //decoding json structure into array
        if (!empty($curlData)) {
            $covertedData = json_decode($curlData, true);
            if (!empty($covertedData)) {
                return current($covertedData);
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public static function clean($string)
    {
        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
        return preg_replace('/[^A-Za-z0-9\-.]/', '', $string); // Removes special chars.
    }

    public function is_url_exist($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
        $raw = curl_exec($ch);
        curl_close($ch);

        var_dump($raw);
        die();
    }

    public function getDocHtml($documentObj, $title, $filter = false, $limit = 50)
    {
        $allDocCount = Document::getRecordCount($filter);
        $total_pages = ceil($allDocCount / $limit);
        $docsHtml = '';
        $docsHtml .= '<div class="title_section">
														<h2>' . $title . '</h2>';
        $docsHtml .= '<div class="pull-right">';
        if (count($documentObj) > 0) {
            $docsHtml .= '<a class="btn btn-green-drake" id="insert_document" onclick="MediaManager.insertDocument();" href="javascript:void(0);" style="padding:4px 12px">Insert Document(s)</a>&nbsp;';
            $docsHtml .= '<a style="padding:4px 12px;margin-right:10px;" class="btn btn-green-drake" id="delete_document" onclick=\'MediaManager.openConfirmBox("document");\' href="javascript:void(0);" >Delete</a>';
        }
        $docsHtml .= '</div></div><div class="clearfix"></div>';
        if (count($documentObj) > 0) {
            $docsHtml .= '<div class="portlet light">
															<div class="scroller gallery">
																<div id="append_user_image">';
            foreach ($documentObj as $key => $value) {
                if ($this->BUCKET_ENABLED) {
                    $documentPath = $this->S3_MEDIA_BUCKET_DOCUMENT_PATH . '/' . $value->txtSrcDocumentName . '.' . $value->varDocumentExtension;
                } else {
                    $documentPath = Config::get('Constant.LOCAL_CDN_PATH') . '/documents/' . $value->txtSrcDocumentName . '.' . $value->varDocumentExtension;
                }

                if ($this->filePathExist($documentPath)) {
                    if ($this->BUCKET_ENABLED) {
                        $docUrl = $this->_APP_URL . $this->S3_MEDIA_BUCKET_DOCUMENT_PATH . '/' . $value->txtSrcDocumentName . '.' . $value->varDocumentExtension;
                    } else {
                        $docUrl = Config::get('Constant.CDN_PATH') . 'documents/' . $value->txtSrcDocumentName . '.' . $value->varDocumentExtension;
                    }

                    $docsHtml .= "<div data-docext='" . $value->varDocumentExtension . "' data-docnm='" . $value->txtDocumentName . "' data-docUrl=" . $docUrl . " class='img-box contains_thumb' id='document_" . $value->id . "'>
																											 <div class='thumbnail_container'>
																													<div class='thumbnail'>
																													 <a  title='" . $value->txtDocumentName . "' href='javascript:void(0);' onclick=\"MediaManager.selectDocument('" . $value->id . "')\" >";
                    if (strtolower($value->varDocumentExtension) == "pdf") {
                        $docsHtml .= "<img alt='" . $value->txtDocumentName . "' src='" . $this->_APP_URL . "assets/images/documents_logo/pdf.png'><span class=\"mob_doc\"><i data-bs-toggle='tooltip' data-bs-placement='top' class='ri-cellphone-line' title='View: " . $value->intMobileViewCount . "\nDownload: " . $value->intMobileDownloadCount . "'></i></span><span class=\"mob_desk\"><i data-bs-toggle='tooltip' data-bs-placement='top' class='ri-computer-line' title='View: " . $value->intDesktopViewCount . "\nDownload: " . $value->intDesktopDownloadCount . "'></i></span><span class=\"mob_copy\"><i class='ri-clipboard-line doc-copy' data-docurl=" . $docUrl . " data-bs-toggle='tooltip' data-bs-placement='top' title='Copy Link'></i></span>";
                    } elseif (strtolower($value->varDocumentExtension) == "xls" || $value->varDocumentExtension == "xlsx" || $value->varDocumentExtension == "xlsm") {
                        $docsHtml .= "<img alt='" . $value->txtDocumentName . "' src='" . $this->_APP_URL . "assets/images/documents_logo/xls.png'><span class=\"mob_doc\"><i data-bs-toggle='tooltip' data-bs-placement='top' class='ri-cellphone-line' title='View: " . $value->intMobileViewCount . "\nDownload: " . $value->intMobileDownloadCount . "'></i></span><span class=\"mob_desk\"><i data-bs-toggle='tooltip' data-bs-placement='top' class='ri-computer-line' title='View: " . $value->intDesktopViewCount . "\nDownload: " . $value->intDesktopDownloadCount . "'></i></span><span class=\"mob_copy\"><i class='ri-clipboard-line doc-copy' data-docurl=" . $docUrl . " data-bs-toggle='tooltip' data-bs-placement='top' title='Copy Link'></i></span>";
                    } elseif (strtolower($value->varDocumentExtension) == "docx" || strtolower($value->varDocumentExtension) == "doc") {
                        $docsHtml .= "<img alt='" . $value->txtDocumentName . "' src='" . $this->_APP_URL . "assets/images/documents_logo/doc.png'><span class=\"mob_doc\"><i data-bs-toggle='tooltip' data-bs-placement='top' class='ri-cellphone-line' title='View: " . $value->intMobileViewCount . "\nDownload: " . $value->intMobileDownloadCount . "'></i></span><span class=\"mob_desk\"><i data-bs-toggle='tooltip' data-bs-placement='top' class='ri-computer-line' title='View: " . $value->intDesktopViewCount . "\nDownload: " . $value->intDesktopDownloadCount . "'></i></span><span class=\"mob_copy\"><i class='ri-clipboard-line doc-copy' data-docurl=" . $docUrl . " data-bs-toggle='tooltip' data-bs-placement='top' title='Copy Link'></i></span>";
                    } elseif (strtolower($value->varDocumentExtension) == "ppt") {
                        $docsHtml .= "<img alt='" . $value->txtDocumentName . "' src='" . $this->_APP_URL . "assets/images/documents_logo/ppt.png'><span class=\"mob_doc\"><i data-bs-toggle='tooltip' data-bs-placement='top' class='ri-cellphone-line' title='View: " . $value->intMobileViewCount . "\nDownload: " . $value->intMobileDownloadCount . "'></i></span><span class=\"mob_desk\"><i data-bs-toggle='tooltip' data-bs-placement='top' class='ri-computer-line' title='View: " . $value->intDesktopViewCount . "\nDownload: " . $value->intDesktopDownloadCount . "'></i></span><span class=\"mob_copy\"><i class='ri-clipboard-line doc-copy' data-docurl=" . $docUrl . " data-bs-toggle='tooltip' data-bs-placement='top' title='Copy Link'></i></span>";
                    } elseif (strtolower($value->varDocumentExtension) == "txt") {
                        $docsHtml .= "<img alt='" . $value->txtDocumentName . "' src='" . $this->_APP_URL . "assets/images/documents_logo/txt.png'><span class=\"mob_doc\"><i data-bs-toggle='tooltip' data-bs-placement='top' class='ri-cellphone-line' title='View: " . $value->intMobileViewCount . "\nDownload: " . $value->intMobileDownloadCount . "'></i></span><span class=\"mob_desk\"><i data-bs-toggle='tooltip' data-bs-placement='top' class='ri-computer-line' title='View: " . $value->intDesktopViewCount . "\nDownload: " . $value->intDesktopDownloadCount . "'></i></span><span class=\"mob_copy\"><i class='ri-clipboard-line doc-copy' data-docurl=" . $docUrl . " data-bs-toggle='tooltip' data-bs-placement='top' title='Copy Link'></i></span>";
                    } else {
                        $docsHtml .= "<img alt='" . $value->txtDocumentName . "' src='" . $this->_APP_URL . "assets/images/documents_logo/document_icon.png'><span class=\"mob_doc\"><i data-bs-toggle='tooltip' data-bs-placement='top' class='ri-cellphone-line' title='View: " . $value->intMobileViewCount . "\nDownload: " . $value->intMobileDownloadCount . "'></i></span><span class=\"mob_desk\"><i data-bs-toggle='tooltip' data-bs-placement='top' class='ri-computer-line' title='View: " . $value->intDesktopViewCount . "\nDownload: " . $value->intDesktopDownloadCount . "'></i></span><span class=\"mob_copy\"><i class='ri-clipboard-line doc-copy' data-docurl=" . $docUrl . " data-bs-toggle='tooltip' data-bs-placement='top' title='Copy Link'></i></span>";
                    }

                    $docsHtml .= "<span class='icon-check' aria-hidden='true'></span>
																								</a>
																								</div>
																						</div>
																																																			<div class='title-change'>
															<input class='form-control' type='text' name='documentname" . $value->id . "' id='documentname_" . $value->id . "' value='" . $value->txtDocumentName . "'/><a onclick=\"MediaManager.GetUpdateDocumentName('" . $value->id . "')\" href=\"javascript:void(0);\" class='btn'><i class='fa fa-pencil'></i></a>

																																																			</div>
																																																			<a class='right_check' href='javascript:void(0)' ><i class=''></i></a>
																											</div>";
                }
            }
            $docsHtml .= '</div>';
            if ($total_pages > 1) {
                //if (!isset($filter['docName']) || (isset($filter['docName']) && empty($filter['docName']))) {
                $docsHtml .= '<div class="text-center"><a class="btn btn-green-drake upload_image_load" id="load_more_docs"  onclick="MediaManager.getMoreDocs(' . Auth::user()->id . ');" href="javascript:void(0);">Load More Docs</a></div>';
                //}
            }
            $docsHtml .= '</div><div class="clearfix"></div>';
            //$docsHtml .= '<div class="clearfix"></div></div>';
            $docsHtml .= '<input type="hidden" id="doc_page_no" name="doc_page_no" value="1">';
        } else {
            $docsHtml .= '<div class="portlet light"><h3>Document(s) are not available</h3></div>';
        }
        return $docsHtml;
    }

    public function getFolderDocHtml($documentObj, $title, $filter = false, $limit = 50)
    {

        $allDocCount = Document::getFolderRecordCount($filter);
        $total_pages = ceil($allDocCount / $limit);
        $docsHtml = '';
        $docsHtml .= '<div class="title_section">
		<h2>' . $title . '</h2>';
        $docsHtml .= '<div class="pull-right">';
        if (count($documentObj) > 0) {
            $docsHtml .= '<a class="btn btn-green-drake" id="insert_document" onclick="MediaManager.insertDocument();" href="javascript:void(0);" style="padding:4px 12px">Insert Document(s)</a>&nbsp;';
            $docsHtml .= '<a style="padding:4px 12px;margin-right:10px;" class="btn btn-green-drake" id="delete_document" onclick=\'MediaManager.openConfirmBox("document");\' href="javascript:void(0);" >Delete</a>';
        }
        $docsHtml .= '</div></div><div class="clearfix"></div>';
        if (count($documentObj) > 0) {
            $docsHtml .= '<div class="portlet light">
			<div class="scroller gallery">
			<div id="append_user_image">';
            $docsHtml .= '<div id="folderdocumentreplace">';
            $listfolder = array();
            foreach ($documentObj as $key => $value) {
                if ($this->BUCKET_ENABLED) {
                    $documentPath = $this->S3_MEDIA_BUCKET_DOCUMENT_PATH . '/' . $value->txtSrcDocumentName . '.' . $value->varDocumentExtension;
                } else {
                    $documentPath = Config::get('Constant.LOCAL_CDN_PATH') . '/documents/' . $value->txtSrcDocumentName . '.' . $value->varDocumentExtension;
                }

                if (isset($value->varfolder) && $value->varfolder == 'folder') {
                    $folderdata = Document::getFolderName($value->fk_folder);

                    if ($folderdata != '') {
                        if (!in_array($folderdata->id, $listfolder)) {
                            $docsHtml .= "<div class='img-box contains_thumb' style='margin-bottom:15px;' id='media_" . $value->id . "' data-order='" . $key . "'><a id='media_" . $value->id . "' title='" . $folderdata->foldername . "' href='javascript:void(0);' onclick='FolderDocument(" . $value->fk_folder . ")' ><img alt='" . $folderdata->foldername . "' src='" . Config::get('Constant.CDN_PATH') . 'assets/images/folder.png' . "' ><span>" . $folderdata->foldername . "</span></a></div>";
                            array_push($listfolder, $folderdata->id);
                        }
                    }
                } else {
                    $docsHtml .= '';
                }
            }
            $docsHtml .= '</div></div><div class="clearfix"></div></div></div>';
            $docsHtml .= '<input type="hidden" id="doc_page_no" name="doc_page_no" value="1">';
        } else {
            $docsHtml .= '<div class="portlet light"><h3>Document(s) are not available</h3></div>';
        }
        return $docsHtml;
    }

    public function getAudioHtml($audioObj, $title, $filter = false, $limit = 50)
    {
        $allAudioCount = Audio::getRecordCount($filter);
        $total_pages = ceil($allAudioCount / $limit);
        $audiosHtml = '';
        $audiosHtml .= '<div class="title_section">
				<h2>' . $title . '</h2>';
        $audiosHtml .= '<div class="pull-right">';
        if (count($audioObj) > 0) {
            $audiosHtml .= '<a class="btn btn-green-drake" id="insert_audio" onclick="MediaManager.insertAudio();" href="javascript:void(0);" style="padding:4px 12px">Insert Audio(s)</a>&nbsp;';
            $audiosHtml .= '<a style="padding:4px 12px;margin-right:10px;" class="btn btn-green-drake" id="delete_audio" onclick=\'MediaManager.openConfirmBox("audio");\' href="javascript:void(0);" >Delete</a>';
        }
        $audiosHtml .= '</div></div><div class="clearfix"></div>';
        if (count($audioObj) > 0) {
            $audiosHtml .= '<div class="portlet light">
															<div class="scroller gallery">
																<div id="append_user_image">';
            foreach ($audioObj as $key => $value) {
                if ($this->BUCKET_ENABLED) {
                    $audioPath = $this->S3_MEDIA_BUCKET_AUDIO_PATH . '/' . $value->txtSrcAudioName . '.' . $value->varAudioExtension;
                } else {
                    $audioPath = Config::get('Constant.LOCAL_CDN_PATH') . '/audios/' . $value->txtSrcAudioName . '.' . $value->varAudioExtension;
                }

                if ($this->filePathExist($audioPath)) {
                    if ($this->BUCKET_ENABLED) {
                        $audioUrl = $this->_APP_URL . $this->S3_MEDIA_BUCKET_AUDIO_PATH . '/' . $value->txtSrcAudioName . '.' . $value->varAudioExtension;
                    } else {
                        $audioUrl = Config::get('Constant.CDN_PATH') . '/audios/' . $value->txtSrcAudioName . '.' . $value->varAudioExtension;
                    }

                    $audiosHtml .= "<div data-audioext='" . $value->varAudioExtension . "' data-audionm='" . $value->txtAudioName . "' data-audioUrl=" . $audioUrl . " class='img-box contains_thumb' id='audio_" . $value->id . "' style='margin-bottom: 60px;'>
																											 <div class='thumbnail_container'>
																													<div class='thumbnail'>
																													 <a  title='" . $value->txtAudioName . "' href='javascript:void(0);' onclick=\"MediaManager.selectAudio('" . $value->id . "')\" >";
                    $audiosHtml .= "<audio id='myAudio_" . $value->id . "'><source src='" . $audioUrl . "' type='audio/mpeg'></audio>";
                    $audiosHtml .= '<input type="hidden" id="audioid" value="' . $value->id . '">';
                    if (strtolower($value->varAudioExtension) == "mp3") {
                        $audiosHtml .= "<img alt='" . $value->txtAudioName . "' src='" . $this->_APP_URL . "assets/images/audios_logo/mp3.png'>";
                    } elseif (strtolower($value->varAudioExtension) == "wav") {
                        $audiosHtml .= "<img alt='" . $value->txtAudioName . "' src='" . $this->_APP_URL . "assets/images/audios_logo/wav.png'>";
                    } elseif (strtolower($value->varAudioExtension) == "mp4") {
                        $audiosHtml .= "<img alt='" . $value->txtAudioName . "' src='" . $this->_APP_URL . "assets/images/audios_logo/mp4.png'>";
                    } else {
                        $audiosHtml .= "<img alt='" . $value->txtAudioName . "' src='" . $this->_APP_URL . "assets/images/audios_logo/audio_icon.png'>";
                    }

                    $audiosHtml .= "<span class='icon-check' aria-hidden='true'></span>
																								</a>
																																																																																																 <div class='mp3_overlay'><span><a onclick=\"Playaudio('" . $value->id . "')\"><div id='audiohtml_" . $value->id . "'><i class='fa fa-play' title='Play'></i></div></a></span></div>
																								</div>
																						</div>
																																																			<div class='title-change'>
															<input class='form-control' type='text' name='audioname" . $value->id . "' id='audioname_" . $value->id . "' value='" . $value->txtAudioName . "'/><a onclick=\"MediaManager.GetUpdateAudioName('" . $value->id . "')\" href=\"javascript:void(0);\" class='btn'><i class='fa fa-pencil'></i></a>

																																																			</div>
																																																			<a class='right_check' href='javascript:void(0)' ><i class=''></i></a>
																											</div>";
                }
            }
            $audiosHtml .= '</div>';
            if ($total_pages > 1) {
                //if (!isset($filter['audioName']) || (isset($filter['audioName']) && empty($filter['audioName']))) {
                $audiosHtml .= '<a class="btn btn-green-drake upload_image_load" id="load_more_audios"  onclick="MediaManager.getMoreAudios(' . Auth::user()->id . ');" href="javascript:void(0);">Load More Audios</a>&nbsp;';
                //}
            }
            $audiosHtml .= '</div><div class="clearfix"></div>';
            //$audiosHtml .= '<div class="clearfix"></div></div>';
            $audiosHtml .= '<input type="hidden" id="audio_page_no" name="audio_page_no" value="1">';
        } else {
            $audiosHtml .= '<div class="portlet light"><h3>Audio(s) are not available</h3></div>';
        }
        return $audiosHtml;
    }

    public function getFolderAudioHtml($audioObj, $title, $filter = false, $limit = 50)
    {
        $allAudioCount = Audio::getRecordCount($filter);
        $total_pages = ceil($allAudioCount / $limit);
        $audiosHtml = '';
        $audiosHtml .= '<div class="title_section">
				<h2>' . $title . '</h2>';
        $audiosHtml .= '<div class="pull-right">';
        if (count($audioObj) > 0) {
            $audiosHtml .= '<a class="btn btn-green-drake" id="insert_audio" onclick="MediaManager.insertAudio();" href="javascript:void(0);" style="padding:4px 12px">Insert Audio(s)</a>&nbsp;';
            $audiosHtml .= '<a style="padding:4px 12px;margin-right:10px;" class="btn btn-green-drake" id="delete_audio" onclick=\'MediaManager.openConfirmBox("audio");\' href="javascript:void(0);" >Delete</a>';
        }
        $audiosHtml .= '</div></div><div class="clearfix"></div>';
        if (count($audioObj) > 0) {
            $audiosHtml .= '<div class="portlet light">
		<div class="scroller gallery">
		<div id="append_user_image">';
            $audiosHtml .= '<div id="folderaudioreplace">';
            $listfolder = array();
            foreach ($audioObj as $key => $value) {
                if ($this->BUCKET_ENABLED) {
                    $audioPath = $this->S3_MEDIA_BUCKET_AUDIO_PATH . '/' . $value->txtSrcAudioName . '.' . $value->varAudioExtension;
                } else {
                    $audioPath = Config::get('Constant.LOCAL_CDN_PATH') . '/audios/' . $value->txtSrcAudioName . '.' . $value->varAudioExtension;
                }

                if (isset($value->varfolder) && $value->varfolder == 'folder') {
                    $folderdata = Audio::getFolderName($value->fk_folder);

                    if ($folderdata != '') {
                        if (!in_array($folderdata->id, $listfolder)) {
                            $audiosHtml .= "<div class='img-box contains_thumb' style='margin-bottom: 60px;' id='media_" . $value->id . "' data-order='" . $key . "'><a id='media_" . $value->id . "' title='" . $folderdata->foldername . "' href='javascript:void(0);' onclick='FolderAudio(" . $value->fk_folder . ")' ><img alt='" . $folderdata->foldername . "' src='" . Config::get('Constant.CDN_PATH') . '/assets/images/folder.png' . "' ><span>" . $folderdata->foldername . "</span></a></div>";
                            array_push($listfolder, $folderdata->id);
                        }
                    }
                } else {
                    $audiosHtml .= '';
                }
            }
            $audiosHtml .= '</div>';
            if ($total_pages > 1) {
                //if (!isset($filter['audioName']) || (isset($filter['audioName']) && empty($filter['audioName']))) {
                $audiosHtml .= '<a class="btn btn-green-drake upload_image_load" id="load_more_audios"  onclick="MediaManager.getMoreAudios(' . Auth::user()->id . ');" href="javascript:void(0);">Load More Audios</a>&nbsp;';
                //}
            }
            $audiosHtml .= '</div><div class="clearfix"></div>';
            //$audiosHtml .= '<div class="clearfix"></div></div>';
            $audiosHtml .= '<input type="hidden" id="audio_page_no" name="audio_page_no" value="1">';
        } else {
            $audiosHtml .= '<div class="portlet light"><h3>Audio(s) are not available</h3></div>';
        }
        return $audiosHtml;
    }

    public function getImageHtml($imageObj, $title, $filter = false, $limit = 50)
    {

        $allImageCount = Image::getRecordCount($filter);
        $total_pages = ceil($allImageCount / $limit);
        $Image_html = '<div class="title_section">';
        $Image_html .= '<h2>' . $title . '</h2>';
        $Image_html .= '<div class="pull-right">';

        if ($imageObj->count() > 0) {
            $Image_html .= '<a class="btn btn-green-drake" id="insert_image" onclick="MediaManager.insertMedia();" href="javascript:void(0);" style="padding:4px 12px">Insert Media</a>&nbsp;';
            $Image_html .= '<a class="btn btn-green-drake" id="delete_image" style="padding:4px 12px;margin-right:10px;" onclick=\'MediaManager.openConfirmBox("Image");\' href="javascript:void(0);" >Delete</a>';
        }

        $Image_html .= '</div></div><div class="clearfix"></div>';

        if ($imageObj->count() > 0) {

            $Image_html .= '<div class="portlet light">';
            $Image_html .= '<p id="note"></p>';
            $Image_html .= '<div class="scroller gallery">';
            $Image_html .= '<div id="append_user_image">';
            $Image_html .= '<div id="folderimagereplace">';
            foreach ($imageObj as $key => $value) {
                if ($this->BUCKET_ENABLED) {
                    $img_path = $this->S3_MEDIA_BUCKET_PATH . '/' . $value->txtImageName . '.' . $value->varImageExtension;
                } else {
                    $img_path = Config::get('Constant.LOCAL_CDN_PATH') . '/assets/images/' . $value->txtImageName . '.' . $value->varImageExtension;
                }
                if ($this->filePathExist($img_path)) {
                    if ($this->BUCKET_ENABLED) {
                        $img_main_src = $this->_APP_URL . $this->S3_MEDIA_BUCKET_PATH . '/' . $value->txtImageName . '.' . $value->varImageExtension;
                    } else {
                        $img_main_src = Config::get('Constant.CDN_PATH') . '/assets/images/' . $value->txtImageName . '.' . $value->varImageExtension;
                    }

                    $Image_html .= "<div class='img-box contains_thumb' id='media_" . $value->id . "' data-order='" . $key . "'>
                                                                                                                                                         <div class='thumbnail_container'>
                                                                                                                                                                        <div class='thumbnail' id='media_image_" . $value->id . "' data-image_big_source='" . $img_main_src . "' data-image_title = '" . $value->txtImgOriginalName . "'>
                                                                                                                                                                         <a  title='" . $value->txtImgOriginalName . "' href='javascript:void(0);' onclick=\"MediaManager.selectImage('" . $value->id . "')\" >
                                                                                                                                                                                                         <img alt='" . $value->txtImgOriginalName . "' src='" . resize_image::resize($value->id, 195, 195) . "'>
                                                                                                                                                                                                         <span class='icon-check' aria-hidden='true'></span>
                                                                                                                                                                                        </a>
                                                                                                                                                                                        </div>
                                                                                                                                                                        </div>
                                                                                                                                                                        <a class='right_check text_doc' href='javascript:void(0)' ><i class=''></i></a>";
                    $Image_html .= "<div class='img-btns'>";
                    $Image_html .= "<a href='javascript:;' title='Image Detail' onclick=\"MediaManager.getImageDetails('" . $value->id . "');\"><span class='icon icon-info'></span></a>";

                    if ($value->varImageExtension != "svg") {
                        $Image_html .= "<a href='javascript:;' title='Crop Image' onclick=\"MediaManager.cropImage('" . $value->id . "');\"><span class='icon-crop'></span></a>";
                    }

                    $Image_html .= "</div></div>";
                }
            }
            $Image_html .= '</div>';
            if ($total_pages > 1) {
                //if (!isset($filter['imageName']) || (isset($filter['imageName']) && empty($filter['imageName']))) {
                $Image_html .= '<div class="text-center"><a class="btn btn-green-drake upload_image_load" id="load_more_images"  onclick="MediaManager.getMoreImages(' . Auth::user()->id . ');" href="javascript:void(0);">Load More Images</a></div>';
                //}
            }
            $Image_html .= '</div><div class="clearfix"></div></div></div>';
            $Image_html .= '<input type="hidden" id="page" name="page" value="1">';
        } else {
            $Image_html .= '<div class="portlet light"><h3>Images are not available</h3></div>';
        }
        return $Image_html;
    }

    public function getFolderImageHtml($imageObj, $title, $filter = false, $limit = 50)
    {

        $allImageCount = Image::getFolderRecordCount($filter);
        $total_pages = ceil($allImageCount / $limit);
        $Image_html = '<div class="title_section">';
        $Image_html .= '<h2>' . $title . '</h2>';
        $Image_html .= '<div class="pull-right">';

        if ($imageObj->count() > 0) {
            $Image_html .= '<a class="btn btn-green-drake" id="insert_image" onclick="MediaManager.insertMedia();" href="javascript:void(0);" style="padding:4px 12px">Insert Media</a>&nbsp;';
            $Image_html .= '<a class="btn btn-green-drake" id="delete_image" style="padding:4px 12px;margin-right:10px;" onclick=\'MediaManager.openConfirmBox("Image");\' href="javascript:void(0);" >Delete</a>';
        }

        $Image_html .= '</div></div><div class="clearfix"></div>';

        if ($imageObj->count() > 0) {

            $Image_html .= '<div class="portlet light">';
            $Image_html .= '<p id="note"></p>';
            $Image_html .= '<div class="scroller gallery">';
            $Image_html .= '<div id="append_user_image">';
            $Image_html .= '<div id="folderimagereplace">';
            $listfolder = array();
            foreach ($imageObj as $key => $value) {
                if ($this->BUCKET_ENABLED) {
                    $img_path = $this->S3_MEDIA_BUCKET_PATH . '/' . $value->txtImageName . '.' . $value->varImageExtension;
                } else {
                    $img_path = Config::get('Constant.LOCAL_CDN_PATH') . '/assets/images/' . $value->txtImageName . '.' . $value->varImageExtension;
                }
                if (isset($value->varfolder) && $value->varfolder == 'folder') {
                    $folderdata = Image::getFolderName($value->fk_folder);
                    if ($folderdata != '') {
                        if (!in_array($folderdata->id, $listfolder)) {
                            $Image_html .= "<div class='img-box contains_thumb' style='margin-bottom: 60px;' id='media_" . $value->id . "' data-order='" . $key . "'><a id='media_" . $value->id . "' title='" . $folderdata->foldername . "' href='javascript:void(0);' onclick='FolderImages(" . $value->fk_folder . ")' ><img alt='" . $folderdata->foldername . "' src='" . Config::get('Constant.CDN_PATH') . '/assets/images/folder.png' . "' ><span>" . $folderdata->foldername . "</span></a></div>";
                            array_push($listfolder, $folderdata->id);
                        }
                    }
                }
            }
            $Image_html .= '</div>';
            $Image_html .= '</div><div class="clearfix"></div></div></div>';
            $Image_html .= '<input type="hidden" id="page" name="page" value="1">';
        } else {
            $Image_html .= '<div class="portlet light"><h3>Images are not available</h3></div>';
        }
        return $Image_html;
    }

    public function removeFiles($filePath = false, $files = false)
    {
        $response = false;
        if ($filePath) {
            if (is_array($files)) {
                foreach ($files as $file) {
                    $fileExistPath = $filePath . $file;
                    if ($this->filePathExist($fileExistPath)) {
                        if ($this->BUCKET_ENABLED) {
                            Aws_File_helper::deleteObject($fileExistPath);
                            /* Aws_File_helper::createInvalidation('/assets/*'); */
                        } else {
                            unlink($fileExistPath);
                        }
                    }
                }
            } else {
                $fileExistPath = $filePath . $files;
                if ($this->filePathExist($fileExistPath)) {
                    if ($this->BUCKET_ENABLED) {
                        Aws_File_helper::deleteObject($fileExistPath);
                        /* Aws_File_helper::createInvalidation('/assets/*'); */
                    } else {
                        unlink($fileExistPath);
                    }
                }
            }
            $response = true;
        }
        return $response;
    }

    public function filePathExist($filepath = false)
    {
        $response = false;
        if ($this->BUCKET_ENABLED) {
            if (Aws_File_helper::checkObjectExists($filepath)) {
                $response = true;
            }
        } else {
            if (file_exists($filepath)) {
                $response = true;
            }
        }

        return $response;
    }

    public static function checkedUsedImg()
    {
        $response = [];
        $exists = ImgModuleRel::getRecordCheckImageUsed(Request::get('idArr'));
        if (!empty($exists)) {
            $response = [];
            $response['usedImg'] = $exists;
            $response['message'] = "Image(s) cannot be deleted as it has been assigned in one or more records!";
            $response = json_encode($response);
        }
        return $response;
    }

    public static function checkedUsedVideo()
    {
        $response = [];
        $exists = VideoModuleRel::getRecord(Request::get('idArr'))->toArray();
        if (!empty($exists)) {
            $response = [];
            $response['usedVideo'] = $exists;
            $response['message'] = "Video(s) cannot be deleted as it has been assigned in one or more records!";
            $response = json_encode($response);
        }
        return $response;
    }

    public static function checkedUsedDocument()
    {
        $response = [];
        $exists = DocumentModuleRel::getRecordCheckDocUsed(Request::get('idArr'));
        if (!empty($exists)) {
            $response = [];
            $response['usedDocument'] = $exists;
            $response['message'] = "Document(s) cannot be deleted as it has been assigned in one or more records!";
            $response = json_encode($response);
        }
        return $response;
    }

    public static function checkedUsedAudio()
    {
        $response = [];
        $exists = AudioModuleRel::getRecordCheckAudioUsed(Request::get('idArr'));
        if (!empty($exists)) {
            $response = [];
            $response['usedAudioument'] = $exists;
            $response['message'] = "Audio(s) cannot be deleted as it has been assigned in one or more records!";
            $response = json_encode($response);
        }
        return $response;
    }

    public function restore_multiple_image()
    {
        $response = false;
        if (Request::get('idArr')) {
            $response = Image::whereIn('id', Request::get('idArr'))->update(['chrPublish' => 'Y', 'chrDelete' => 'N']);
        }
        echo $response;
        exit;
    }

    public function restore_multiple_videos()
    {
        $response = false;
        if (Request::get('idArr')) {
            $response = Video::whereIn('id', Request::get('idArr'))->update(['chrPublish' => 'Y', 'chrDelete' => 'N']);
        }
        echo $response;
        exit;
    }

    public function restore_multiple_document()
    {
        $response = false;
        if (Request::get('idArr')) {
            $response = Document::whereIn('id', Request::get('idArr'))->update(['chrPublish' => 'Y', 'chrDelete' => 'N']);
        }
        echo $response;
        exit;
    }

    public function restore_multiple_audio()
    {
        $response = false;
        if (Request::get('idArr')) {
            $response = Audio::whereIn('id', Request::get('idArr'))->update(['chrPublish' => 'Y', 'chrDelete' => 'N']);
        }
        echo $response;
        exit;
    }

    public function ComposerDocData()
    {
        $record = Request::post();
        $docsAray = explode(',', $record['id']);
        $docObj = Document::getDocDataByIds($docsAray);
        $html = '';

        if (count($docObj) > 0) {
            foreach ($docObj as $value) {
                $html .= '<li id="doc_' . $value->id . '">
											<span title="' . $value->txtDocumentName . $value->varDocumentExtension . '">
													<img  src="' . $this->_APP_URL . 'assets/images/document_icon.png" alt="Img" />
													<a href="javascript:;" onclick="MediaManager.removeDocumentFromComposerBlock(' . $value->id . ');" class="delect_image" data-dismiss="fileinput"><i class="ri-delete-bin-line"></i></a>
											</span>
											<span class="editdoctitle">' . $value->txtDocumentName . '.' . $value->varDocumentExtension . '</span>
									</li>';
            }
        }
        return $html;
    }

    public function ComposerDocDatajs()
    {
        $record = Request::post();
        $docsAray = explode(',', $record['id']);
        $docObj = Document::getDocDataByIds($docsAray);
        $html = '';
        $html .= '<div class="builder_doc_list">
                    <ul class="grid_dochtml">';
        if (count($docObj) > 0) {
            foreach ($docObj as $value) {
                $html .= '<li id="doc_' . $value->id . '">
										<span title="' . $value->txtDocumentName . $value->varDocumentExtension . '">
												<img  src="' . $this->_APP_URL . 'assets/images/document_icon.png" alt="Img" />
										</span>
										<span class="editdoctitle">' . $value->txtDocumentName . '.' . $value->varDocumentExtension . '</span>
								</li>';
            }
        }
        
        $html .= '</ul></div>';
        $html .= '<input type="hidden" id="dochiddenid" name="img1" value="' . $record['id'] . '">';

        return $html;
    }

    public static function formatSizeUnits($bytes)
    {
        if ($bytes >= 1073741824) {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            $bytes = $bytes . ' bytes';
        } elseif ($bytes == 1) {
            $bytes = $bytes . ' byte';
        } else {
            $bytes = '0 bytes';
        }

        return $bytes;
    }

    public function GetFolderImages()
    {
        $html = '';
        $folder_data = DB::table('image')
            ->select('*')
            ->where('fk_folder', '=', $_REQUEST['fid'])
            ->where('chrPublish', '=', 'Y')
            ->where('chrDelete', '=', 'N')
            ->get();

        $i = 0;
        foreach ($folder_data as $fdata) {
            $folderdata = Image::getFolderName($fdata->fk_folder);

            if ($_REQUEST['imgIDs'] == $fdata->id) {
                $class = 'img-box-active';
                $iconclass = 'icon-check icons';
            } else {
                $class = '';
                $iconclass = '';
            }
            $img_path = Config::get('Constant.CDN_PATH') . '/assets/images/' . $folderdata->foldername . '/' . $fdata->txtImageName . '.' . $fdata->varImageExtension;
            $html .= "<div class=\"img-box contains_thumb " . $class . "\" id='media_" . $fdata->id . "' data-folder='" . $fdata->fk_folder . "' data-order='" . $i . "'>
                     <div class='thumbnail_container'>
                        <div class='thumbnail' id='media_image_" . $fdata->id . "' data-image_big_source='" . $img_path . "' data-image_title='" . $fdata->txtImgOriginalName . "'>
                           <a title='" . $fdata->txtImgOriginalName . "' href=\"javascript:void(0);\" onclick=\"MediaManager.selectImage('" . $fdata->id . "')\">
                           <img alt='" . $fdata->txtImgOriginalName . "' src='" . $img_path . "'>
                           <span class=\"icon-check\" aria-hidden=\"true\"></span>
                           </a>
                        </div>
                     </div>
                     <a class=\"right_check text_doc\" href=\"javascript:void(0)\"><i class=\"" . $iconclass . "\"></i></a>
                     <div class=\"img-btns\"><a href=\"javascript:;\" title=\"Image Detail\" onclick=\"MediaManager.getImageDetails('" . $fdata->id . "','" . $fdata->fk_folder . "');\"><span class=\"icon icon-info\"></span></a><a href=\"javascript:;\" title=\"Crop Image\" onclick=\"MediaManager.cropImage('" . $fdata->id . "','" . $fdata->fk_folder . "');\"><span class=\"icon-crop\"></span></a></div>
                  </div>";
            $i++;
        }

        echo $html;
        exit;
    }

    public function GetFolderAudio()
    {
        $html = '';
        $folder_data = DB::table('audios')
            ->select('*')
            ->where('fk_folder', '=', $_REQUEST['fid'])
            ->where('chrPublish', '=', 'Y')
            ->where('chrDelete', '=', 'N')
            ->get();

        $i = 0;
        foreach ($folder_data as $fdata) {
            $folderdata = Audio::getFolderName($fdata->fk_folder);

            if ($_REQUEST['imgIDs'] == $fdata->id) {
                $class = 'img-box-active';
                $iconclass = 'icon-check icons';
            } else {
                $class = '';
                $iconclass = '';
            }
            $audioUrl = Config::get('Constant.CDN_PATH') . '/audios/' . $folderdata->foldername . '/' . $fdata->txtSrcAudioName . '.' . $fdata->varAudioExtension;
            $html .= "<div data-audioext=\"" . $fdata->varAudioExtension . "\" data-audionm=\"" . $fdata->txtAudioName . "\" data-audiourl=\"" . $audioUrl . "\" class=\"img-box contains_thumb " . $class . "\" id=\"audio_" . $fdata->id . "\" style='margin-bottom: 60px;'>
    <div class=\"thumbnail_container\">
      <div class=\"thumbnail\">
         <a title=\"" . $fdata->txtAudioName . "\" href=\"javascript:void(0);\" onclick=\"MediaManager.selectAudio('" . $fdata->id . "')\">
            <audio id=\"myAudio_" . $fdata->id . "\">
               <source src=\"" . $audioUrl . "\" type=\"audio/mpeg\">
            </audio>
            <input type=\"hidden\" id=\"audioid\" value=\"" . $fdata->id . "\">";
            if (strtolower($fdata->varAudioExtension) == "mp3") {
                $html .= "<img alt='" . $fdata->txtAudioName . "' src='" . Config::get('Constant.CDN_PATH') . "assets/images/audios_logo/mp3.png'>";
            } elseif (strtolower($fdata->varAudioExtension) == "wav") {
                $html .= "<img alt='" . $fdata->txtAudioName . "' src='" . Config::get('Constant.CDN_PATH') . "assets/images/audios_logo/wav.png'>";
            } elseif (strtolower($fdata->varAudioExtension) == "mp4") {
                $html .= "<img alt='" . $fdata->txtAudioName . "' src='" . Config::get('Constant.CDN_PATH') . "assets/images/audios_logo/mp4.png'>";
            } else {
                $html .= "<img alt='" . $fdata->txtAudioName . "' src='" . Config::get('Constant.CDN_PATH') . "assets/images/audios_logo/audio_icon.png'>";
            }

            $html .= "<span class='icon-check' aria-hidden='true'></span></a>
                <div class=\"mp3_overlay\">
                   <span>
                      <a onclick=\"Playaudio('" . $fdata->id . "')\">
                         <div id=\"audiohtml_" . $fdata->id . "\"><i class=\"fa fa-play\" title=\"Play\"></i></div>
                      </a>
                   </span>
                </div>
             </div>
          </div>
          <div class=\"title-change\">
             <input class=\"form-control\" type=\"text\" name=\"audioname" . $fdata->id . "\" id=\"audioname_" . $fdata->id . "\" value=\"" . $fdata->txtAudioName . "\"><a onclick=\"MediaManager.GetUpdateAudioName('" . $fdata->id . "')\" href=\"javascript:void(0);\" class=\"btn\"><i class=\"fa fa-pencil\"></i></a>
          </div>
          <a class=\"right_check\" href=\"javascript:void(0)\"><i class=\"" . $iconclass . "\"></i></a></div>";
            $i++;
        }

        echo $html;
        exit;
    }

    public function GetFolderDocument()
    {
        $html = '';
        $folder_data = DB::table('documents')
            ->select('*')
            ->where('fk_folder', '=', $_REQUEST['fid'])
            ->where('chrPublish', '=', 'Y')
            ->where('chrDelete', '=', 'N')
            ->get();

        $i = 0;
        foreach ($folder_data as $fdata) {
            $folderdata = Document::getFolderName($fdata->fk_folder);
            $expload = explode(",", $_REQUEST['imgIDs']);
            if (in_array($fdata->id, $expload)) {
                $class = 'img-box-active';
                $iconclass = 'icon-check icons';
            } else {
                $class = '';
                $iconclass = '';
            }
            $docUrl = Config::get('Constant.CDN_PATH') . '/documents/' . $folderdata->foldername . '/' . $fdata->txtSrcDocumentName . '.' . $fdata->varDocumentExtension;
            $html .= "<div data-docext=\"" . $fdata->varDocumentExtension . "\" data-docnm=\"" . $fdata->txtDocumentName . "\" data-folder='" . $fdata->fk_folder . "' data-docurl=\"" . $docUrl . "\" class=\"img-box contains_thumb " . $class . "\" id=\"document_" . $fdata->id . "\">
                <div class=\"thumbnail_container\">
                   <div class=\"thumbnail\">
                      <a title=\"" . $fdata->txtDocumentName . "\" href=\"javascript:void(0);\" onclick=\"MediaManager.selectDocument('" . $fdata->id . "')\">";
            if (strtolower($fdata->varDocumentExtension) == "pdf") {
                $html .= "<img alt='" . $fdata->txtDocumentName . "' src='" . Config::get('Constant.CDN_PATH') . "assets/images/documents_logo/pdf.png'><span class=\"mob_doc\"><i data-bs-toggle='tooltip' data-bs-placement='top' class='ri-cellphone-line' title='View: " . $fdata->intMobileViewCount . "\nDownload: " . $fdata->intMobileDownloadCount . "'></i></span><span class=\"mob_desk\"><i data-bs-toggle='tooltip' data-bs-placement='top' class='ri-computer-line' title='View: " . $fdata->intDesktopViewCount . "\nDownload: " . $fdata->intDesktopDownloadCount . "'></i></span><span class=\"mob_copy\"><i class='ri-clipboard-line doc-copy' data-docurl=\"" . $docUrl . "\" data-bs-toggle='tooltip' data-bs-placement='top' title='Copy Link'></i></span>";
            } elseif (strtolower($fdata->varDocumentExtension) == "xls" || $fdata->varDocumentExtension == "xlsx" || $fdata->varDocumentExtension == "xlsm") {
                $html .= "<img alt='" . $fdata->txtDocumentName . "' src='" . Config::get('Constant.CDN_PATH') . "assets/images/documents_logo/xls.png'><span class=\"mob_doc\"><i data-bs-toggle='tooltip' data-bs-placement='top' class='ri-cellphone-line' title='View: " . $fdata->intMobileViewCount . "\nDownload: " . $fdata->intMobileDownloadCount . "'></i></span><span class=\"mob_desk\"><i data-bs-toggle='tooltip' data-bs-placement='top' class='ri-computer-line' title='View: " . $fdata->intDesktopViewCount . "\nDownload: " . $fdata->intDesktopDownloadCount . "'></i></span><span class=\"mob_copy\"><i class='ri-clipboard-line doc-copy' data-docurl=\"" . $docUrl . "\" data-bs-toggle='tooltip' data-bs-placement='top' title='Copy Link'></i></span>";
            } elseif (strtolower($fdata->varDocumentExtension) == "docx" || strtolower($fdata->varDocumentExtension) == "doc") {
                $html .= "<img alt='" . $fdata->txtDocumentName . "' src='" . Config::get('Constant.CDN_PATH') . "assets/images/documents_logo/doc.png'><span class=\"mob_doc\"><i data-bs-toggle='tooltip' data-bs-placement='top' class='ri-cellphone-line' title='View: " . $fdata->intMobileViewCount . "\nDownload: " . $fdata->intMobileDownloadCount . "'></i></span><span class=\"mob_desk\"><i data-bs-toggle='tooltip' data-bs-placement='top' class='ri-computer-line' title='View: " . $fdata->intDesktopViewCount . "\nDownload: " . $fdata->intDesktopDownloadCount . "'></i></span><span class=\"mob_copy\"><i class='ri-clipboard-line doc-copy' data-docurl=\"" . $docUrl . "\" data-bs-toggle='tooltip' data-bs-placement='top' title='Copy Link'></i></span>";
            } elseif (strtolower($fdata->varDocumentExtension) == "ppt") {
                $html .= "<img alt='" . $fdata->txtDocumentName . "' src='" . Config::get('Constant.CDN_PATH') . "assets/images/documents_logo/ppt.png'><span class=\"mob_doc\"><i data-bs-toggle='tooltip' data-bs-placement='top' class='ri-cellphone-line' title='View: " . $fdata->intMobileViewCount . "\nDownload: " . $fdata->intMobileDownloadCount . "'></i></span><span class=\"mob_desk\"><i data-bs-toggle='tooltip' data-bs-placement='top' class='ri-computer-line' title='View: " . $fdata->intDesktopViewCount . "\nDownload: " . $fdata->intDesktopDownloadCount . "'></i></span><span class=\"mob_copy\"><i class='ri-clipboard-line doc-copy' data-docurl=\"" . $docUrl . "\" data-bs-toggle='tooltip' data-bs-placement='top' title='Copy Link'></i></span>";
            } elseif (strtolower($fdata->varDocumentExtension) == "txt") {
                $html .= "<img alt='" . $fdata->txtDocumentName . "' src='" . Config::get('Constant.CDN_PATH') . "assets/images/documents_logo/txt.png'><span class=\"mob_doc\"><i data-bs-toggle='tooltip' data-bs-placement='top' class='ri-cellphone-line' title='View: " . $fdata->intMobileViewCount . "\nDownload: " . $fdata->intMobileDownloadCount . "'></i></span><span class=\"mob_desk\"><i data-bs-toggle='tooltip' data-bs-placement='top' class='ri-computer-line' title='View: " . $fdata->intDesktopViewCount . "\nDownload: " . $fdata->intDesktopDownloadCount . "'></i></span><span class=\"mob_copy\"><i class='ri-clipboard-line doc-copy' data-docurl=\"" . $docUrl . "\" data-bs-toggle='tooltip' data-bs-placement='top' title='Copy Link'></i></span>";
            } else {
                $html .= "<img alt='" . $fdata->txtDocumentName . "' src='" . Config::get('Constant.CDN_PATH') . "assets/images/documents_logo/document_icon.png'><span class=\"mob_doc\"><i data-bs-toggle='tooltip' data-bs-placement='top' class='ri-cellphone-line' title='View: " . $fdata->intMobileViewCount . "\nDownload: " . $fdata->intMobileDownloadCount . "'></i></span><span class=\"mob_desk\"><i data-bs-toggle='tooltip' data-bs-placement='top' class='ri-computer-line' title='View: " . $fdata->intDesktopViewCount . "\nDownload: " . $fdata->intDesktopDownloadCount . "'></i></span><span class=\"mob_copy\"><i class='ri-clipboard-line doc-copy' data-docurl=\"" . $docUrl . "\" data-bs-toggle='tooltip' data-bs-placement='top' title='Copy Link'></i></span>";
            }
            $html .= "<span class=\"icon-check\" aria-hidden=\"true\"></span>
                      </a>
                   </div>
                </div>
                <div class=\"title-change\">
                   <input class=\"form-control\" type=\"text\" name=\"documentname" . $fdata->id . "\" id=\"documentname_" . $fdata->id . "\" value=\"" . $fdata->txtDocumentName . "\"><a onclick=\"MediaManager.GetUpdateDocumentName('" . $fdata->id . "')\" href=\"javascript:void(0);\" class=\"btn\"><i class=\"fa fa-pencil\"></i></a>
                </div>
                <a class=\"right_check\" href=\"javascript:void(0)\"><i class=\"" . $iconclass . "\"></i></a>
             </div>";
            $i++;
        }
        echo $html;
        exit;
    }

    public function HideColumn()
    {
        $record = Request::input();
        if ($record['column_disp'] == 'N') {
            $DataArr = array();
            $DataArr['moduleid'] = $record['moduleid'];
            $DataArr['chrtab'] = $record['tabid'];
            $DataArr['columnno'] = $record['columnno'];
            $DataArr['columnname'] = $record['columnname'];
            $DataArr['columnid'] = $record['columnid'];
            $DataArr['UserID'] = auth()->user()->id;
            DB::table('gridsetting')->insert($DataArr);
        } else {
            DB::table('gridsetting')->where('moduleid', $record['moduleid'])->where('chrtab', $record['tabid'])->where('columnno', $record['columnno'])->where('columnname', $record['columnname'])->where('columnid', $record['columnid'])->where('UserID', auth()->user()->id)->delete();
        }
    }

}
