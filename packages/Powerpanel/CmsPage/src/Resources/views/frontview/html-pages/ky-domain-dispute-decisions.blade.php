<section class="inner-page-gap whois-information">
    @include('layouts.share-email-print')    

    <div class="container">
        <div class="row">
            <div class="col-xl-3 left-panel">
                <div class="nav-overlay" onclick="closeNav1()"></div>
                <div class="text-right">
                    <a href="javascript:void(0)" onclick="openNav1()" id="menu__open1" title="Filter & Menu" class="short-menu">Filter & Menu</a>
                </div>
                <div class="menu1" id="menu1">
                    <div class="row n-mr-xl-15" data-aos="fade-up">
                        <div class="col-12">
                            <article>
                                <div class="nqtitle-small lp-title text-uppercase n-mb-25">ICT Information</div>
                                <div class="s-list">
                                    @include('cmspage::frontview.ict-left-panel')
                                </div>
                            </article>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-9 n-mt-25 n-mt-xl-0" data-aos="fade-up">
                <div class="cms">
                    <table>
                        <tr>
                            <th>Year</th>
                            <th>Company Name</th>
                            <th>Desc.</th>
                            <th>Document</th>
                        </tr>
                        <tr>
                            <td>2016</td>
                            <td>.KY DISPUTE RESOLUTION</td>
                            <td>ICTA Decision D-0002</td>
                            <td><a href="#" title="Download" download="">Download</a></td>
                        </tr>
                        <tr>
                            <td>2014</td>
                            <td>Artisan Metal Works</td>
                            <td>ICTA Decision D-0001</td>
                            <td><a href="#" title="Download" download="">Download</a></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>


