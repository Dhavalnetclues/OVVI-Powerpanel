<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=1" />
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="http://localhost/project-name/front-html/assets/css/main.css">
    <title>Utilities Documentation!</title>
</head>

<body>
    <?php define('CCSS', "n"); ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-2">
                <ul class="nav flex-column m-2">
                    <li class="nav-item">
                        <a class="mt-2 btn btn-primary nav-link active" id="ud-t-1" data-toggle="tab" href="#ud-1" role="tab" aria-controls="ud-1" aria-selected="true">Background Color</a>
                    </li>
                    <li class="nav-item">
                        <a class="mt-2 btn btn-primary nav-link" id="ud-t-2" data-toggle="tab" href="#ud-2" role="tab" aria-controls="ud-2" aria-selected="false">Background Attachment</a>
                    </li>
                    <li class="nav-item">
                        <a class="mt-2 btn btn-primary nav-link" id="ud-t-3" data-toggle="tab" href="#ud-3" role="tab" aria-controls="ud-3" aria-selected="false">Background Position</a>
                    </li>
                    <li class="nav-item">
                        <a class="mt-2 btn btn-primary nav-link" id="ud-t-4" data-toggle="tab" href="#ud-4" role="tab" aria-controls="ud-4" aria-selected="false">Background Repeat</a>
                    </li>
                    <li class="nav-item">
                        <a class="mt-2 btn btn-primary nav-link" id="ud-t-5" data-toggle="tab" href="#ud-5" role="tab" aria-controls="ud-5" aria-selected="false">Background Size</a>
                    </li>
                </ul>
            </div>
            <div class="col-sm-10 mt-4">
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="ud-1" role="tabpanel" aria-labelledby="ud-t-1">
                        <h2>Background Color</h2>
                        <hr>
                        <table class="table table-bordered">
                            <tr>
                                <th colspan="10">White Default Color</th>
                            </tr>
                            <tr>
                                <td class="<?php echo CCSS; ?>-bgc-white-50">White<br>50</td>
                                <td class="<?php echo CCSS; ?>-bgc-white-100">White<br>100</td>
                                <td class="<?php echo CCSS; ?>-bgc-white-200">White<br>200</td>
                                <td class="<?php echo CCSS; ?>-bgc-white-300">White<br>300</td>
                                <td class="<?php echo CCSS; ?>-bgc-white-400">White<br>400</td>
                                <td class="<?php echo CCSS; ?>-bgc-white-500">White<br>500</td>
                                <td class="<?php echo CCSS; ?>-bgc-white-600">White<br>600</td>
                                <td class="<?php echo CCSS; ?>-bgc-white-700">White<br>700</td>
                                <td class="<?php echo CCSS; ?>-bgc-white-800">White<br>800</td>
                                <td class="<?php echo CCSS; ?>-bgc-white-900">White<br>900</td>
                            </tr>
                            <tr>
                                <td><code><?php echo CCSS; ?>-bgc-white-50</code></td>
                                <td><code><?php echo CCSS; ?>-bgc-white-100</code></td>
                                <td><code><?php echo CCSS; ?>-bgc-white-200</code></td>
                                <td><code><?php echo CCSS; ?>-bgc-white-300</code></td>
                                <td><code><?php echo CCSS; ?>-bgc-white-400</code></td>
                                <td><code><?php echo CCSS; ?>-bgc-white-500</code></td>
                                <td><code><?php echo CCSS; ?>-bgc-white-600</code></td>
                                <td><code><?php echo CCSS; ?>-bgc-white-700</code></td>
                                <td><code><?php echo CCSS; ?>-bgc-white-800</code></td>
                                <td><code><?php echo CCSS; ?>-bgc-white-900</code></td>
                            </tr>
                        </table>
                        <table class="table table-bordered">
                            <tr>
                                <th colspan="10">Black Default Color</th>
                            </tr>
                            <tr class="<?php echo CCSS; ?>-fc-white-500">
                                <td class="<?php echo CCSS; ?>-bgc-black-50">Black<br>50</td>
                                <td class="<?php echo CCSS; ?>-bgc-black-100">Black<br>100</td>
                                <td class="<?php echo CCSS; ?>-bgc-black-200">Black<br>200</td>
                                <td class="<?php echo CCSS; ?>-bgc-black-300">Black<br>300</td>
                                <td class="<?php echo CCSS; ?>-bgc-black-400">Black<br>400</td>
                                <td class="<?php echo CCSS; ?>-bgc-black-500">Black<br>500</td>
                                <td class="<?php echo CCSS; ?>-bgc-black-600">Black<br>600</td>
                                <td class="<?php echo CCSS; ?>-bgc-black-700">Black<br>700</td>
                                <td class="<?php echo CCSS; ?>-bgc-black-800">Black<br>800</td>
                                <td class="<?php echo CCSS; ?>-bgc-black-900">Black<br>900</td>
                            </tr>
                            <tr>
                                <td><code><?php echo CCSS; ?>-bgc-black-50</code></td>
                                <td><code><?php echo CCSS; ?>-bgc-black-100</code></td>
                                <td><code><?php echo CCSS; ?>-bgc-black-200</code></td>
                                <td><code><?php echo CCSS; ?>-bgc-black-300</code></td>
                                <td><code><?php echo CCSS; ?>-bgc-black-400</code></td>
                                <td><code><?php echo CCSS; ?>-bgc-black-500</code></td>
                                <td><code><?php echo CCSS; ?>-bgc-black-600</code></td>
                                <td><code><?php echo CCSS; ?>-bgc-black-700</code></td>
                                <td><code><?php echo CCSS; ?>-bgc-black-800</code></td>
                                <td><code><?php echo CCSS; ?>-bgc-black-900</code></td>
                            </tr>
                        </table>
                        <table class="table table-bordered">
                            <tr>
                                <th colspan="10">Light Default Color</th>
                            </tr>
                            <tr class="<?php echo CCSS; ?>-fc-black-500">
                                <td class="<?php echo CCSS; ?>-bgc-light-50">Light<br>50</td>
                                <td class="<?php echo CCSS; ?>-bgc-light-100">Light<br>100</td>
                                <td class="<?php echo CCSS; ?>-bgc-light-200">Light<br>200</td>
                                <td class="<?php echo CCSS; ?>-bgc-light-300">Light<br>300</td>
                                <td class="<?php echo CCSS; ?>-bgc-light-400">Light<br>400</td>
                                <td class="<?php echo CCSS; ?>-bgc-light-500">Light<br>500</td>
                                <td class="<?php echo CCSS; ?>-bgc-light-600">Light<br>600</td>
                                <td class="<?php echo CCSS; ?>-bgc-light-700">Light<br>700</td>
                                <td class="<?php echo CCSS; ?>-bgc-light-800">Light<br>800</td>
                                <td class="<?php echo CCSS; ?>-bgc-light-900">Light<br>900</td>
                            </tr>
                            <tr>
                                <td><code><?php echo CCSS; ?>-bgc-light-50</code></td>
                                <td><code><?php echo CCSS; ?>-bgc-light-100</code></td>
                                <td><code><?php echo CCSS; ?>-bgc-light-200</code></td>
                                <td><code><?php echo CCSS; ?>-bgc-light-300</code></td>
                                <td><code><?php echo CCSS; ?>-bgc-light-400</code></td>
                                <td><code><?php echo CCSS; ?>-bgc-light-500</code></td>
                                <td><code><?php echo CCSS; ?>-bgc-light-600</code></td>
                                <td><code><?php echo CCSS; ?>-bgc-light-700</code></td>
                                <td><code><?php echo CCSS; ?>-bgc-light-800</code></td>
                                <td><code><?php echo CCSS; ?>-bgc-light-900</code></td>
                            </tr>
                        </table>
                        <table class="table table-bordered">
                            <tr>
                                <th colspan="10">Grey Default Color</th>
                            </tr>
                            <tr class="<?php echo CCSS; ?>-fc-white-500">
                                <td class="<?php echo CCSS; ?>-bgc-grey-50">Grey<br>50</td>
                                <td class="<?php echo CCSS; ?>-bgc-grey-100">Grey<br>100</td>
                                <td class="<?php echo CCSS; ?>-bgc-grey-200">Grey<br>200</td>
                                <td class="<?php echo CCSS; ?>-bgc-grey-300">Grey<br>300</td>
                                <td class="<?php echo CCSS; ?>-bgc-grey-400">Grey<br>400</td>
                                <td class="<?php echo CCSS; ?>-bgc-grey-500">Grey<br>500</td>
                                <td class="<?php echo CCSS; ?>-bgc-grey-600">Grey<br>600</td>
                                <td class="<?php echo CCSS; ?>-bgc-grey-700">Grey<br>700</td>
                                <td class="<?php echo CCSS; ?>-bgc-grey-800">Grey<br>800</td>
                                <td class="<?php echo CCSS; ?>-bgc-grey-900">Grey<br>900</td>
                            </tr>
                            <tr>
                                <td><code><?php echo CCSS; ?>-bgc-grey-50</code></td>
                                <td><code><?php echo CCSS; ?>-bgc-grey-100</code></td>
                                <td><code><?php echo CCSS; ?>-bgc-grey-200</code></td>
                                <td><code><?php echo CCSS; ?>-bgc-grey-300</code></td>
                                <td><code><?php echo CCSS; ?>-bgc-grey-400</code></td>
                                <td><code><?php echo CCSS; ?>-bgc-grey-500</code></td>
                                <td><code><?php echo CCSS; ?>-bgc-grey-600</code></td>
                                <td><code><?php echo CCSS; ?>-bgc-grey-700</code></td>
                                <td><code><?php echo CCSS; ?>-bgc-grey-800</code></td>
                                <td><code><?php echo CCSS; ?>-bgc-grey-900</code></td>
                            </tr>
                        </table>
                        <table class="table table-bordered">
                            <tr>
                                <th colspan="10">Dark Default Color</th>
                            </tr>
                            <tr class="<?php echo CCSS; ?>-fc-white-500">
                                <td class="<?php echo CCSS; ?>-bgc-dark-50">Dark<br>50</td>
                                <td class="<?php echo CCSS; ?>-bgc-dark-100">Dark<br>100</td>
                                <td class="<?php echo CCSS; ?>-bgc-dark-200">Dark<br>200</td>
                                <td class="<?php echo CCSS; ?>-bgc-dark-300">Dark<br>300</td>
                                <td class="<?php echo CCSS; ?>-bgc-dark-400">Dark<br>400</td>
                                <td class="<?php echo CCSS; ?>-bgc-dark-500">Dark<br>500</td>
                                <td class="<?php echo CCSS; ?>-bgc-dark-600">Dark<br>600</td>
                                <td class="<?php echo CCSS; ?>-bgc-dark-700">Dark<br>700</td>
                                <td class="<?php echo CCSS; ?>-bgc-dark-800">Dark<br>800</td>
                                <td class="<?php echo CCSS; ?>-bgc-dark-900">Dark<br>900</td>
                            </tr>
                            <tr>
                                <td><code><?php echo CCSS; ?>-bgc-dark-50</code></td>
                                <td><code><?php echo CCSS; ?>-bgc-dark-100</code></td>
                                <td><code><?php echo CCSS; ?>-bgc-dark-200</code></td>
                                <td><code><?php echo CCSS; ?>-bgc-dark-300</code></td>
                                <td><code><?php echo CCSS; ?>-bgc-dark-400</code></td>
                                <td><code><?php echo CCSS; ?>-bgc-dark-500</code></td>
                                <td><code><?php echo CCSS; ?>-bgc-dark-600</code></td>
                                <td><code><?php echo CCSS; ?>-bgc-dark-700</code></td>
                                <td><code><?php echo CCSS; ?>-bgc-dark-800</code></td>
                                <td><code><?php echo CCSS; ?>-bgc-dark-900</code></td>
                            </tr>
                        </table>
                        <table class="table table-bordered">
                            <tr>
                                <th colspan="10">A Color</th>
                            </tr>
                            <tr class="<?php echo CCSS; ?>-fc-white-500">
                                <td class="<?php echo CCSS; ?>-bgc-a-50">A<br>50</td>
                                <td class="<?php echo CCSS; ?>-bgc-a-100">A<br>100</td>
                                <td class="<?php echo CCSS; ?>-bgc-a-200">A<br>200</td>
                                <td class="<?php echo CCSS; ?>-bgc-a-300">A<br>300</td>
                                <td class="<?php echo CCSS; ?>-bgc-a-400">A<br>400</td>
                                <td class="<?php echo CCSS; ?>-bgc-a-500">A<br>500</td>
                                <td class="<?php echo CCSS; ?>-bgc-a-600">A<br>600</td>
                                <td class="<?php echo CCSS; ?>-bgc-a-700">A<br>700</td>
                                <td class="<?php echo CCSS; ?>-bgc-a-800">A<br>800</td>
                                <td class="<?php echo CCSS; ?>-bgc-a-900">A<br>900</td>
                            </tr>
                            <tr>
                                <td><code><?php echo CCSS; ?>-bgc-a-50</code></td>
                                <td><code><?php echo CCSS; ?>-bgc-a-100</code></td>
                                <td><code><?php echo CCSS; ?>-bgc-a-200</code></td>
                                <td><code><?php echo CCSS; ?>-bgc-a-300</code></td>
                                <td><code><?php echo CCSS; ?>-bgc-a-400</code></td>
                                <td><code><?php echo CCSS; ?>-bgc-a-500</code></td>
                                <td><code><?php echo CCSS; ?>-bgc-a-600</code></td>
                                <td><code><?php echo CCSS; ?>-bgc-a-700</code></td>
                                <td><code><?php echo CCSS; ?>-bgc-a-800</code></td>
                                <td><code><?php echo CCSS; ?>-bgc-a-900</code></td>
                            </tr>
                        </table>
                        <table class="table table-bordered">
                            <tr>
                                <th colspan="10">B Color</th>
                            </tr>
                            <tr class="<?php echo CCSS; ?>-fc-white-500">
                                <td class="<?php echo CCSS; ?>-bgc-b-50">B<br>50</td>
                                <td class="<?php echo CCSS; ?>-bgc-b-100">B<br>100</td>
                                <td class="<?php echo CCSS; ?>-bgc-b-200">B<br>200</td>
                                <td class="<?php echo CCSS; ?>-bgc-b-300">B<br>300</td>
                                <td class="<?php echo CCSS; ?>-bgc-b-400">B<br>400</td>
                                <td class="<?php echo CCSS; ?>-bgc-b-500">B<br>500</td>
                                <td class="<?php echo CCSS; ?>-bgc-b-600">B<br>600</td>
                                <td class="<?php echo CCSS; ?>-bgc-b-700">B<br>700</td>
                                <td class="<?php echo CCSS; ?>-bgc-b-800">B<br>800</td>
                                <td class="<?php echo CCSS; ?>-bgc-b-900">B<br>900</td>
                            </tr>
                            <tr>
                                <td><code><?php echo CCSS; ?>-bgc-b-50</code></td>
                                <td><code><?php echo CCSS; ?>-bgc-b-100</code></td>
                                <td><code><?php echo CCSS; ?>-bgc-b-200</code></td>
                                <td><code><?php echo CCSS; ?>-bgc-b-300</code></td>
                                <td><code><?php echo CCSS; ?>-bgc-b-400</code></td>
                                <td><code><?php echo CCSS; ?>-bgc-b-500</code></td>
                                <td><code><?php echo CCSS; ?>-bgc-b-600</code></td>
                                <td><code><?php echo CCSS; ?>-bgc-b-700</code></td>
                                <td><code><?php echo CCSS; ?>-bgc-b-800</code></td>
                                <td><code><?php echo CCSS; ?>-bgc-b-900</code></td>
                            </tr>
                        </table>
                        <table class="table table-bordered">
                            <tr>
                                <th colspan="10">C Color</th>
                            </tr>
                            <tr class="<?php echo CCSS; ?>-fc-white-500">
                                <td class="<?php echo CCSS; ?>-bgc-c-50">C<br>50</td>
                                <td class="<?php echo CCSS; ?>-bgc-c-100">C<br>100</td>
                                <td class="<?php echo CCSS; ?>-bgc-c-200">C<br>200</td>
                                <td class="<?php echo CCSS; ?>-bgc-c-300">C<br>300</td>
                                <td class="<?php echo CCSS; ?>-bgc-c-400">C<br>400</td>
                                <td class="<?php echo CCSS; ?>-bgc-c-500">C<br>500</td>
                                <td class="<?php echo CCSS; ?>-bgc-c-600">C<br>600</td>
                                <td class="<?php echo CCSS; ?>-bgc-c-700">C<br>700</td>
                                <td class="<?php echo CCSS; ?>-bgc-c-800">C<br>800</td>
                                <td class="<?php echo CCSS; ?>-bgc-c-900">C<br>900</td>
                            </tr>
                            <tr>
                                <td><code><?php echo CCSS; ?>-bgc-c-50</code></td>
                                <td><code><?php echo CCSS; ?>-bgc-c-100</code></td>
                                <td><code><?php echo CCSS; ?>-bgc-c-200</code></td>
                                <td><code><?php echo CCSS; ?>-bgc-c-300</code></td>
                                <td><code><?php echo CCSS; ?>-bgc-c-400</code></td>
                                <td><code><?php echo CCSS; ?>-bgc-c-500</code></td>
                                <td><code><?php echo CCSS; ?>-bgc-c-600</code></td>
                                <td><code><?php echo CCSS; ?>-bgc-c-700</code></td>
                                <td><code><?php echo CCSS; ?>-bgc-c-800</code></td>
                                <td><code><?php echo CCSS; ?>-bgc-c-900</code></td>
                            </tr>
                        </table>
                        <table class="table table-bordered">
                            <tr>
                                <th colspan="10">Error Default Color</th>
                            </tr>
                            <tr class="<?php echo CCSS; ?>-fc-white-500">
                                <td class="<?php echo CCSS; ?>-bgc-error-50">Error<br>50</td>
                                <td class="<?php echo CCSS; ?>-bgc-error-100">Error<br>100</td>
                                <td class="<?php echo CCSS; ?>-bgc-error-200">Error<br>200</td>
                                <td class="<?php echo CCSS; ?>-bgc-error-300">Error<br>300</td>
                                <td class="<?php echo CCSS; ?>-bgc-error-400">Error<br>400</td>
                                <td class="<?php echo CCSS; ?>-bgc-error-500">Error<br>500</td>
                                <td class="<?php echo CCSS; ?>-bgc-error-600">Error<br>600</td>
                                <td class="<?php echo CCSS; ?>-bgc-error-700">Error<br>700</td>
                                <td class="<?php echo CCSS; ?>-bgc-error-800">Error<br>800</td>
                                <td class="<?php echo CCSS; ?>-bgc-error-900">Error<br>900</td>
                            </tr>
                            <tr>
                                <td><code><?php echo CCSS; ?>-bgc-error-50</code></td>
                                <td><code><?php echo CCSS; ?>-bgc-error-100</code></td>
                                <td><code><?php echo CCSS; ?>-bgc-error-200</code></td>
                                <td><code><?php echo CCSS; ?>-bgc-error-300</code></td>
                                <td><code><?php echo CCSS; ?>-bgc-error-400</code></td>
                                <td><code><?php echo CCSS; ?>-bgc-error-500</code></td>
                                <td><code><?php echo CCSS; ?>-bgc-error-600</code></td>
                                <td><code><?php echo CCSS; ?>-bgc-error-700</code></td>
                                <td><code><?php echo CCSS; ?>-bgc-error-800</code></td>
                                <td><code><?php echo CCSS; ?>-bgc-error-900</code></td>
                            </tr>
                        </table>
                        <table class="table table-bordered">
                            <tr>
                                <th colspan="10">Warning Default Color</th>
                            </tr>
                            <tr class="<?php echo CCSS; ?>-fc-white-500">
                                <td class="<?php echo CCSS; ?>-bgc-warning-50">Warning<br>50</td>
                                <td class="<?php echo CCSS; ?>-bgc-warning-100">Warning<br>100</td>
                                <td class="<?php echo CCSS; ?>-bgc-warning-200">Warning<br>200</td>
                                <td class="<?php echo CCSS; ?>-bgc-warning-300">Warning<br>300</td>
                                <td class="<?php echo CCSS; ?>-bgc-warning-400">Warning<br>400</td>
                                <td class="<?php echo CCSS; ?>-bgc-warning-500">Warning<br>500</td>
                                <td class="<?php echo CCSS; ?>-bgc-warning-600">Warning<br>600</td>
                                <td class="<?php echo CCSS; ?>-bgc-warning-700">Warning<br>700</td>
                                <td class="<?php echo CCSS; ?>-bgc-warning-800">Warning<br>800</td>
                                <td class="<?php echo CCSS; ?>-bgc-warning-900">Warning<br>900</td>
                            </tr>
                            <tr>
                                <td><code><?php echo CCSS; ?>-bgc-warning-50</code></td>
                                <td><code><?php echo CCSS; ?>-bgc-warning-100</code></td>
                                <td><code><?php echo CCSS; ?>-bgc-warning-200</code></td>
                                <td><code><?php echo CCSS; ?>-bgc-warning-300</code></td>
                                <td><code><?php echo CCSS; ?>-bgc-warning-400</code></td>
                                <td><code><?php echo CCSS; ?>-bgc-warning-500</code></td>
                                <td><code><?php echo CCSS; ?>-bgc-warning-600</code></td>
                                <td><code><?php echo CCSS; ?>-bgc-warning-700</code></td>
                                <td><code><?php echo CCSS; ?>-bgc-warning-800</code></td>
                                <td><code><?php echo CCSS; ?>-bgc-warning-900</code></td>
                            </tr>
                        </table>
                        <table class="table table-bordered">
                            <tr>
                                <th colspan="10">Success Default Color</th>
                            </tr>
                            <tr class="<?php echo CCSS; ?>-fc-white-500">
                                <td class="<?php echo CCSS; ?>-bgc-success-50">Success<br>50</td>
                                <td class="<?php echo CCSS; ?>-bgc-success-100">Success<br>100</td>
                                <td class="<?php echo CCSS; ?>-bgc-success-200">Success<br>200</td>
                                <td class="<?php echo CCSS; ?>-bgc-success-300">Success<br>300</td>
                                <td class="<?php echo CCSS; ?>-bgc-success-400">Success<br>400</td>
                                <td class="<?php echo CCSS; ?>-bgc-success-500">Success<br>500</td>
                                <td class="<?php echo CCSS; ?>-bgc-success-600">Success<br>600</td>
                                <td class="<?php echo CCSS; ?>-bgc-success-700">Success<br>700</td>
                                <td class="<?php echo CCSS; ?>-bgc-success-800">Success<br>800</td>
                                <td class="<?php echo CCSS; ?>-bgc-success-900">Success<br>900</td>
                            </tr>
                            <tr>
                                <td><code><?php echo CCSS; ?>-bgc-success-50</code></td>
                                <td><code><?php echo CCSS; ?>-bgc-success-100</code></td>
                                <td><code><?php echo CCSS; ?>-bgc-success-200</code></td>
                                <td><code><?php echo CCSS; ?>-bgc-success-300</code></td>
                                <td><code><?php echo CCSS; ?>-bgc-success-400</code></td>
                                <td><code><?php echo CCSS; ?>-bgc-success-500</code></td>
                                <td><code><?php echo CCSS; ?>-bgc-success-600</code></td>
                                <td><code><?php echo CCSS; ?>-bgc-success-700</code></td>
                                <td><code><?php echo CCSS; ?>-bgc-success-800</code></td>
                                <td><code><?php echo CCSS; ?>-bgc-success-900</code></td>
                            </tr>
                        </table>
                        <table class="table table-bordered">
                            <tr>
                                <th colspan="10">Info Default Color</th>
                            </tr>
                            <tr class="<?php echo CCSS; ?>-fc-white-500">
                                <td class="<?php echo CCSS; ?>-bgc-info-50">Info<br>50</td>
                                <td class="<?php echo CCSS; ?>-bgc-info-100">Info<br>100</td>
                                <td class="<?php echo CCSS; ?>-bgc-info-200">Info<br>200</td>
                                <td class="<?php echo CCSS; ?>-bgc-info-300">Info<br>300</td>
                                <td class="<?php echo CCSS; ?>-bgc-info-400">Info<br>400</td>
                                <td class="<?php echo CCSS; ?>-bgc-info-500">Info<br>500</td>
                                <td class="<?php echo CCSS; ?>-bgc-info-600">Info<br>600</td>
                                <td class="<?php echo CCSS; ?>-bgc-info-700">Info<br>700</td>
                                <td class="<?php echo CCSS; ?>-bgc-info-800">Info<br>800</td>
                                <td class="<?php echo CCSS; ?>-bgc-info-900">Info<br>900</td>
                            </tr>
                            <tr>
                                <td><code><?php echo CCSS; ?>-bgc-info-50</code></td>
                                <td><code><?php echo CCSS; ?>-bgc-info-100</code></td>
                                <td><code><?php echo CCSS; ?>-bgc-info-200</code></td>
                                <td><code><?php echo CCSS; ?>-bgc-info-300</code></td>
                                <td><code><?php echo CCSS; ?>-bgc-info-400</code></td>
                                <td><code><?php echo CCSS; ?>-bgc-info-500</code></td>
                                <td><code><?php echo CCSS; ?>-bgc-info-600</code></td>
                                <td><code><?php echo CCSS; ?>-bgc-info-700</code></td>
                                <td><code><?php echo CCSS; ?>-bgc-info-800</code></td>
                                <td><code><?php echo CCSS; ?>-bgc-info-900</code></td>
                            </tr>
                        </table>
                    </div>
                    <div class="tab-pane fade show active" id="ud-2" role="tabpanel" aria-labelledby="ud-t-2">
                        <h2>Background Attachment</h2>
                        <hr>
                        <table class="table table-bordered">
                            <tr>
                                <th>Class Name</th>
                                <th>Description</th>
                                <th>@extend Property</th>
                            </tr>
                            <tr>
                                <td><code><?php echo CCSS; ?>-bga-s</code></td>
                                <td>Applies <code>background-attachment: scroll;</code> to element</td>
                                <td><code>@extend .<?php echo CCSS; ?>-bga-s;</code></td>
                            </tr>
                            <tr>
                                <td><code><?php echo CCSS; ?>-bga-f</code></td>
                                <td>Applies <code>background-attachment: fixed;</code> to element</td>
                                <td><code>@extend .<?php echo CCSS; ?>-bga-f;</code></td>
                            </tr>
                            <tr>
                                <td><code><?php echo CCSS; ?>-bga-l</code></td>
                                <td>Applies <code>background-attachment: local;</code> to element</td>
                                <td><code>@extend .<?php echo CCSS; ?>-bga-l;</code></td>
                            </tr>
                            <tr>
                                <td><code><?php echo CCSS; ?>-bga-r</code></td>
                                <td>Applies <code>background-attachment: revert;</code> to element</td>
                                <td><code>@extend .<?php echo CCSS; ?>-bga-r;</code></td>
                            </tr>
                            <tr>
                                <td><code><?php echo CCSS; ?>-bga-ini</code></td>
                                <td>Applies <code>background-attachment: initial;</code> to element</td>
                                <td><code>@extend .<?php echo CCSS; ?>-bga-ini;</code></td>
                            </tr>
                            <tr>
                                <td><code><?php echo CCSS; ?>-bga-inh</code></td>
                                <td>Applies <code>background-attachment: inherit;</code> to element</td>
                                <td><code>@extend .<?php echo CCSS; ?>-bga-inh;</code></td>
                            </tr>
                            <tr>
                                <td><code><?php echo CCSS; ?>-bga-u</code></td>
                                <td>Applies <code>background-attachment: unset;</code> to element</td>
                                <td><code>@extend .<?php echo CCSS; ?>-bga-u;</code></td>
                            </tr>
                        </table>
                    </div>
                    <div class="tab-pane fade show active" id="ud-3" role="tabpanel" aria-labelledby="ud-t-3">
                        <h2>Background Position</h2>
                        <hr>
                        <table class="table table-bordered">
                            <tr>
                                <th>Class Name</th>
                                <th>Description</th>
                                <th>@extend Property</th>
                            </tr>
                            <tr>
                                <td><code><?php echo CCSS; ?>-bgp-t</code></td>
                                <td>Applies <code>background-position: top;</code> to element</td>
                                <td><code>@extend .<?php echo CCSS; ?>-bgp-t;</code></td>
                            </tr>
                            <tr>
                                <td><code><?php echo CCSS; ?>-bgp-r</code></td>
                                <td>Applies <code>background-position: right;</code> to element</td>
                                <td><code>@extend .<?php echo CCSS; ?>-bgp-r;</code></td>
                            </tr>
                            <tr>
                                <td><code><?php echo CCSS; ?>-bgp-b</code></td>
                                <td>Applies <code>background-position: bottom;</code> to element</td>
                                <td><code>@extend .<?php echo CCSS; ?>-bgp-b;</code></td>
                            </tr>
                            <tr>
                                <td><code><?php echo CCSS; ?>-bgp-l</code></td>
                                <td>Applies <code>background-position: left;</code> to element</td>
                                <td><code>@extend .<?php echo CCSS; ?>-bgp-l;</code></td>
                            </tr>
                            <tr>
                                <td><code><?php echo CCSS; ?>-bgp-lt</code></td>
                                <td>Applies <code>background-position: left top;</code> to element</td>
                                <td><code>@extend .<?php echo CCSS; ?>-bgp-lt;</code></td>
                            </tr>
                            <tr>
                                <td><code><?php echo CCSS; ?>-bgp-lc</code></td>
                                <td>Applies <code>background-position: left center;</code> to element</td>
                                <td><code>@extend .<?php echo CCSS; ?>-bgp-lc;</code></td>
                            </tr>
                            <tr>
                                <td><code><?php echo CCSS; ?>-bgp-lb</code></td>
                                <td>Applies <code>background-position: left bottom;</code> to element</td>
                                <td><code>@extend .<?php echo CCSS; ?>-bgp-lb;</code></td>
                            </tr>
                            <tr>
                                <td><code><?php echo CCSS; ?>-bgp-rt</code></td>
                                <td>Applies <code>background-position: right top;</code> to element</td>
                                <td><code>@extend .<?php echo CCSS; ?>-bgp-rt;</code></td>
                            </tr>
                            <tr>
                                <td><code><?php echo CCSS; ?>-bgp-rc</code></td>
                                <td>Applies <code>background-position: right center;</code> to element</td>
                                <td><code>@extend .<?php echo CCSS; ?>-bgp-rc;</code></td>
                            </tr>
                            <tr>
                                <td><code><?php echo CCSS; ?>-bgp-rb</code></td>
                                <td>Applies <code>background-position: right bottom;</code> to element</td>
                                <td><code>@extend .<?php echo CCSS; ?>-bgp-rb;</code></td>
                            </tr>
                            <tr>
                                <td><code><?php echo CCSS; ?>-bgp-ct</code></td>
                                <td>Applies <code>background-position: center top;</code> to element</td>
                                <td><code>@extend .<?php echo CCSS; ?>-bgp-ct;</code></td>
                            </tr>
                            <tr>
                                <td><code><?php echo CCSS; ?>-bgp-cc</code></td>
                                <td>Applies <code>background-position: center center;</code> to element</td>
                                <td><code>@extend .<?php echo CCSS; ?>-bgp-cc;</code></td>
                            </tr>
                            <tr>
                                <td><code><?php echo CCSS; ?>-bgp-cb</code></td>
                                <td>Applies <code>background-position: center bottom;</code> to element</td>
                                <td><code>@extend .<?php echo CCSS; ?>-bgp-cb;</code></td>
                            </tr>
                            <tr>
                                <td><code><?php echo CCSS; ?>-bgp-inherit</code></td>
                                <td>Applies <code>background-position: inherit;</code> to element</td>
                                <td><code>@extend .<?php echo CCSS; ?>-bgp-inherit;</code></td>
                            </tr>
                            <tr>
                                <td><code><?php echo CCSS; ?>-bgp-initial</code></td>
                                <td>Applies <code>background-position: initial;</code> to element</td>
                                <td><code>@extend .<?php echo CCSS; ?>-bgp-initial;</code></td>
                            </tr>
                            <tr>
                                <td><code><?php echo CCSS; ?>-bgp-unset</code></td>
                                <td>Applies <code>background-position: unset;</code> to element</td>
                                <td><code>@extend .<?php echo CCSS; ?>-bgp-unset;</code></td>
                            </tr>
                        </table>
                    </div>
                    <div class="tab-pane fade show active" id="ud-4" role="tabpanel" aria-labelledby="ud-t-4">
                        <h2>Background Repeat</h2>
                        <hr>
                        <table class="table table-bordered">
                            <tr>
                                <th>Class Name</th>
                                <th>Description</th>
                                <th>@extend Property</th>
                            </tr>
                            <tr>
                                <td><code><?php echo CCSS; ?>-bgr-repeat</code></td>
                                <td>Applies <code>background-repeat: repeat;</code> to element</td>
                                <td><code>@extend .<?php echo CCSS; ?>-bgr-repeat;</code></td>
                            </tr>
                            <tr>
                                <td><code><?php echo CCSS; ?>-bgr-repeat-x</code></td>
                                <td>Applies <code>background-repeat: repeat-x;</code> to element</td>
                                <td><code>@extend .<?php echo CCSS; ?>-bgr-repeat-x;</code></td>
                            </tr>
                            <tr>
                                <td><code><?php echo CCSS; ?>-bgr-repeat-y</code></td>
                                <td>Applies <code>background-repeat: repeat-y;</code> to element</td>
                                <td><code>@extend .<?php echo CCSS; ?>-bgr-repeat-y;</code></td>
                            </tr>
                            <tr>
                                <td><code><?php echo CCSS; ?>-bgr-no-repeat</code></td>
                                <td>Applies <code>background-repeat: no-repeat;</code> to element</td>
                                <td><code>@extend .<?php echo CCSS; ?>-bgr-no-repeat;</code></td>
                            </tr>
                            <tr>
                                <td><code><?php echo CCSS; ?>-bgr-space</code></td>
                                <td>Applies <code>background-repeat: space;</code> to element</td>
                                <td><code>@extend .<?php echo CCSS; ?>-bgr-space;</code></td>
                            </tr>
                            <tr>
                                <td><code><?php echo CCSS; ?>-bgr-round</code></td>
                                <td>Applies <code>background-repeat: round;</code> to element</td>
                                <td><code>@extend .<?php echo CCSS; ?>-bgr-round;</code></td>
                            </tr>
                            <tr>
                                <td><code><?php echo CCSS; ?>-bgr-initial</code></td>
                                <td>Applies <code>background-repeat: initial;</code> to element</td>
                                <td><code>@extend .<?php echo CCSS; ?>-bgr-initial;</code></td>
                            </tr>
                        </table>
                    </div>
                    <div class="tab-pane fade show active" id="ud-5" role="tabpanel" aria-labelledby="ud-t-5">
                        <h2>Background Size</h2>
                        <hr>
                        <table class="table table-bordered">
                            <tr>
                                <th>Class Name</th>
                                <th>Description</th>
                                <th>@extend Property</th>
                            </tr>
                            <tr>
                                <td><code><?php echo CCSS; ?>-bgs-a</code></td>
                                <td>Applies <code>background-size: auto;</code> to element</td>
                                <td><code>@extend .<?php echo CCSS; ?>-bgs-a;</code></td>
                            </tr>
                            <tr>
                                <td><code><?php echo CCSS; ?>-bgs-cov</code></td>
                                <td>Applies <code>background-size: cover;</code> to element</td>
                                <td><code>@extend .<?php echo CCSS; ?>-bgs-cov;</code></td>
                            </tr>
                            <tr>
                                <td><code><?php echo CCSS; ?>-bgs-con</code></td>
                                <td>Applies <code>background-size: contain;</code> to element</td>
                                <td><code>@extend .<?php echo CCSS; ?>-bgs-con;</code></td>
                            </tr>
                            <tr>
                                <td><code><?php echo CCSS; ?>-bgs-ini</code></td>
                                <td>Applies <code>background-size: initial;</code> to element</td>
                                <td><code>@extend .<?php echo CCSS; ?>-bgs-ini;</code></td>
                            </tr>
                            <tr>
                                <td><code><?php echo CCSS; ?>-bgs-inh</code></td>
                                <td>Applies <code>background-size: inherit;</code> to element</td>
                                <td><code>@extend .<?php echo CCSS; ?>-bgs-inh;</code></td>
                            </tr>
                            <tr>
                                <td><code><?php echo CCSS; ?>-bgs-u</code></td>
                                <td>Applies <code>background-size: unset;</code> to element</td>
                                <td><code>@extend .<?php echo CCSS; ?>-bgs-u;</code></td>
                            </tr>
                            <tr>
                                <td><code><?php echo CCSS; ?>-bgs-100</code></td>
                                <td>Applies <code>background-size: 100%;</code> to element</td>
                                <td><code>@extend .<?php echo CCSS; ?>-bgs-100;</code></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
</body>

</html>