<section class="inner-page-gap bod-meetings">
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
                    <h2>Current Retail Prices in CI$ / Imperial Gallon</h2>
                    <blockquote>
                        <p>as at Tuesday, March 30, 2021</p>
                    </blockquote>
                    <p>The fuel prices below are collected via phone, email and/or by field survey from the fueling outlets (Gas Stations and Marinas) and are currently updated fortnightly. Note that the full report with fuel prices analysis can now be found in the downloadable PDF document below.</p>
                    <p>OfReg encourages the public to call 946-4282 or email us at fuels@ofreg.ky to advise if any of the fuel prices at the fueling outlets are different from the prices provided here.  Please note that fueling outlets may have promotions from time to time, which may not be captured in our fuel price report.</p>
                </div>

                <div class="row n-mt-15">
                    <div class="col-12">
                        <ul class="nav ac-tabs">
                            <li><a class="active" data-toggle="tab" href="#esso">ESSO</a></li>
                            <li><a class="" data-toggle="tab" href="#rubis">Rubis</a></li>
                            <li><a class="" data-toggle="tab" href="#marinas">Marinas</a></li>
                            <li><a class="" data-toggle="tab" href="#independentothers">INDEPENDENT/OTHERS</a></li>
                        </ul>
                        <div class="tab-content ac-content">
                            <div class="tab-pane fade show active" id="esso">
                                <div class="cms">
                                    <table>
                                        <tbody>
                                            <tr>
                                                <th rowspan="2">District</th>
                                                <th rowspan="2">Fuel Station Site/Name</th>
                                                <th colspan="2">Premium Gasoline</th>
                                                <th colspan="2">Regular Gasoline</th>
                                                <th colspan="2">Diesel</th>
                                            </tr>
                                            <tr>
                                                <th>Self Serve</th>
                                                <th>Full Serve</th>
                                                <th>Self Serve</th>
                                                <th>Full Serve</th>
                                                <th>Self Serve</th>
                                                <th>Full Serve</th>
                                            </tr>
                                            <tr>
                                                <td rowspan="2">West Bay</td>
                                                <td>FOUR WINDS</td>
                                                <td>NA</td>
                                                <td>4.32</td>
                                                <td>NA</td>
                                                <td>3.92</td>
                                                <td>NA</td>
                                                <td>3.81</td>
                                            </tr>
                                            <tr>
                                                <td>HELL'S</td>
                                                <td>NA</td>
                                                <td>4.52</td>
                                                <td>NA</td>
                                                <td>4.22</td>
                                                <td>NA</td>
                                                <td>4.05</td>
                                            </tr>
                                            <tr>
                                                <td rowspan="7">George Town</td>
                                                <td>MIKE'S WALKER'S RD</td>
                                                <td>4.40</td>
                                                <td>4.45</td>
                                                <td>4.00</td>
                                                <td>4.05</td>
                                                <td>3.95</td>
                                                <td>4.00</td>
                                            </tr>
                                            <tr>
                                                <td>H&B II SHEDDEN RD</td>
                                                <td>4.45</td>
                                                <td>4.50</td>
                                                <td>4.15</td>
                                                <td>4.19</td>
                                                <td>NA</td>
                                                <td>4.15</td>
                                            </tr>
                                            <tr>
                                                <td>MIKE'S SEVEN MILE</td>
                                                <td>4.35</td>
                                                <td>4.40</td>
                                                <td>3.97</td>
                                                <td>4.02</td>
                                                <td>3.70</td>
                                                <td>3.75</td>
                                            </tr>
                                            <tr>
                                                <td>H&B ONE SEVEN MILE</td>
                                                <td>4.45</td>
                                                <td>4.50</td>
                                                <td>4.15</td>
                                                <td>4.19</td>
                                                <td>NA</td>
                                                <td>4.15</td>
                                            </tr>
                                            <tr>
                                                <td>BROWN'S INDUSTRIAL PARK</td>
                                                <td>4.47</td>
                                                <td>4.52</td>
                                                <td>4.12</td>
                                                <td>4.17</td>
                                                <td>4.05</td>
                                                <td>4.10</td>
                                            </tr>
                                            <tr>
                                                <td>BROWN'S RED BAY</td>
                                                <td>4.30</td>
                                                <td>4.35</td>
                                                <td>4.02</td>
                                                <td>4.07</td>
                                                <td>3.95</td>
                                                <td>4.00</td>
                                            </tr>
                                            <tr>
                                                <td>BARCAM</td>
                                                <td>4.40</td>
                                                <td>4.42</td>
                                                <td>4.10</td>
                                                <td>4.12</td>
                                                <td>4.08</td>
                                                <td>4.10</td>
                                            </tr>
                                            <tr>
                                                <td>Bodden Town</td>
                                                <td>MOSTYN</td>
                                                <td>NA</td>
                                                <td>4.43</td>
                                                <td>NA</td>
                                                <td>4.10</td>
                                                <td>NA</td>
                                                <td>4.20</td>
                                            </tr>
                                            <tr>
                                                <td>North Side</td>
                                                <td>JACK'S II</td>
                                                <td>NA</td>
                                                <td>4.47</td>
                                                <td>NA</td>
                                                <td>4.17</td>
                                                <td>NA</td>
                                                <td>4.10</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="rubis">
                                <div class="cms">
                                    <table>
                                        <tbody>
                                            <tr>
                                                <th rowspan="2">District</th>
                                                <th rowspan="2">Fuel Station Site/Name</th>
                                                <th colspan="2">Premium Gasoline</th>
                                                <th colspan="2">Regular Gasoline</th>
                                                <th colspan="2">Diesel</th>
                                            </tr>
                                            <tr>
                                                <th>Self Serve</th>
                                                <th>Full Serve</th>
                                                <th>Self Serve</th>
                                                <th>Full Serve</th>
                                                <th>Self Serve</th>
                                                <th>Full Serve</td>
                                            </tr>
                                            <tr>
                                                <td rowspan="5">George Town</td>
                                                <td>WALKER'S ROAD</td>
                                                <td>4.35</td>
                                                <td>4.40</td>
                                                <td>4.07</td>
                                                <td>4.12</td>
                                                <td>NA</td>
                                                <td>4.03</td>
                                            </tr>
                                            <tr>
                                                <td>REDBAY (POINT PLEASANT)</td>
                                                <td>4.42</td>
                                                <td>4.47</td>
                                                <td>4.07</td>
                                                <td>4.12</td>
                                                <td>4.08</td>
                                                <td>4.13</td>
                                            </tr>
                                            <tr>
                                                <td>EASTERN AVENUE</td>
                                                <td>4.35</td>
                                                <td>4.40</td>
                                                <td>4.07</td>
                                                <td>4.12</td>
                                                <td>4.03</td>
                                                <td>4.08</td>
                                            </tr>
                                            <tr>
                                                <td>AA SEVEN MILE</td>
                                                <td>4.35</td>
                                                <td>4.40</td>
                                                <td>4.07</td>
                                                <td>4.12</td>
                                                <td>3.81</td>
                                                <td>NA</td>
                                            </tr>
                                            <tr>
                                                <td>JOSE'S</td>
                                                <td>4.40</td>
                                                <td>4.45</td>
                                                <td>4.07</td>
                                                <td>4.12</td>
                                                <td>4.08</td>
                                                <td>4.13</td>
                                            </tr>
                                            <tr>
                                                <td rowspan="2">Bodden Town</td>
                                                <td>SAVANNAH</td>
                                                <td>4.35</td>
                                                <td>4.40</td>
                                                <td>4.07</td>
                                                <td>4.12</td>
                                                <td>4.03</td>
                                                <td>NA</td>
                                            </tr>
                                            <tr>
                                                <td>LORNA'S</td>
                                                <td>NA</td>
                                                <td>4.45</td>
                                                <td>NA</td>
                                                <td>4.09</td>
                                                <td>NA</td>
                                                <td>4.18</td>
                                            </tr>
                                            <tr>
                                                <td>East End </td>
                                                <td>WOODY'S</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>4.07</td>
                                                <td>NA</td>
                                                <td>4.08</td>
                                                <td>NA</td>
                                            </tr>
                                            <tr>
                                                <td rowspan="2">Cayman Brac</td>
                                                <td>WEST END</td>
                                                <td>NA</td>
                                                <td>4.61</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>4.63</td>
                                            </tr>
                                            <tr>
                                                <td>TIB MART</td>
                                                <td>NA</td>
                                                <td>4.61</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                            </tr>
                                            <tr>
                                                <td>Little Cayman </td>
                                                <td>VILLAGE SQUARE</td>
                                                <td>NA</td>
                                                <td>5.54</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>6.05</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="marinas">
                                <div class="cms">
                                    <table>
                                        <tbody>
                                            <tr>
                                                <th rowspan="2">District</th>
                                                <th rowspan="2">Fuel Station Site/Name</th>
                                                <th colspan="2">Premium Gasoline</th>
                                                <th colspan="2">Regular Gasoline</th>
                                                <th colspan="2">Diesel</th>
                                            </tr>
                                            <tr>
                                                <th>Self Serve</th>
                                                <th>Full Serve</th>
                                                <th>Self Serve</th>
                                                <th>Full Serve</th>
                                                <th>Self Serve</th>
                                                <th>Full Serve</td>
                                            </tr>
                                            <tr>
                                                <td rowspan="2">West Bay</td>
                                                <td>MORGANS HARBOUR</td>
                                                <td>NA</td>
                                                <td>4.38</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>4.08</td>
                                            </tr>
                                            <tr>
                                                <td>ANCHOR'S AT CI YACHT CLUB</td>
                                                <td>NA</td>
                                                <td>4.43</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>3.99</td>
                                            </tr>
                                            <tr>
                                                <td rowspan="2">George Town</td>
                                                <td>HARBOUR HOUSE MARINA</td>
                                                <td>4.32</td>
                                                <td>4.44</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>4.37</td>
                                                <td>4.49</td>
                                            </tr>
                                            <tr>
                                                <td>SCOTTS LANDING (BACADERE)</td>
                                                <td>NA</td>
                                                <td>4.42</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>3.81</td>
                                            </tr>
                                            <tr>
                                                <td>North Side</td>
                                                <td>**KAIBO MARINA</td>
                                                <td>4.95</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>NA</td>
                                                <td>4.69</td>
                                                <td>NA</td>
                                            </tr>
                                        </tbody>
                                    </table>                                    
                                </div>
                            </div>

                            <div class="tab-pane fade" id="independentothers">
                                <div class="cms">
                                    <table>
                                        <tbody>
                                            <tr>
                                                <th>District</th>
                                                <th>Fuel Station Site/Name</th>
                                                <th>Service Type</th>
                                                <th colspan="1">Premium Gasoline E10</th>
                                                <th colspan="1">Mid-Grade Gasoline E10</th>
                                                <th colspan="1">Regular Gasoline E10*</th>
                                                <th colspan="1">Bio-Diesel</th>
                                                <th colspan="2">Diesel</th>
                                            </tr>
                                            <tr>
                                                <td rowspan="2">George Town</td>
                                                <td>REFUEL</td>
                                                <td>Self Serve</td>
                                                <td>4.28</td>
                                                <td>4.06</td>
                                                <td>3.86</td>
                                                <td>3.49</td>
                                                <td>3.63</td>
                                            </tr>
                                            <tr>
                                                <td>REFUEL</td>
                                                <td>Full Serve</td>
                                                <td>4.35</td>
                                                <td>4.13</td>
                                                <td>3.93</td>
                                                <td>3.56</td>
                                                <td>3.69</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="cms">
                    <ul>
                        <li>Ethanol Blended Gasoline - 87 Octane - E10</li>
                        <li>Closed on specific days</li>
                        <li>Esso Retail Stations currently have price promotions on Gasoline products on Wednesdays</li>
                    </ul>
                    <blockquote>
                        <p>FUEL SALES PROMOTIONS: The Office is not aware of any current price discount promotions. </p>
                    </blockquote>
                </div>

                <div class="cms n-mt-50">
                    <h2 class="text-center">Current and Historic Retail Fuel Prices PDF Documents</h2>
                </div>

                <div class="row n-mt-15">
                    @php for ($x = 1; $x <= 6; $x++) { @endphp
                        <div class="col-lg-6 d-flex n-gap-2 n-gapp-lg-3 n-gapm-lg-2">
                            <article class="-items w-100 n-bs-1 n-pa-20 n-p-relative n-bgc-white-500">
                                <div class="documents align-items-start">
                                    <div class="-doct-img">
                                        <i class="n-icon" data-icon="s-pdf"></i>
                                        <i class="n-icon" data-icon="s-download"></i>
                                    </div>
                                    <div>
                                        <a class="-link n-ah-a-500" href="#" download="" title="">Minutes of Special Meeting #1 of 2021 signed Redacted(07 - January - 2021)</a>
                                        <ul class="nqul d-flex justify-content-between align-items-center n-mt-15 n-fs-15 n-fw-500 n-ff-2 n-fc-a-500">
                                            <li class="nq-svg align-items-center d-flex"><i class="n-icon" data-icon="s-calendar"></i> 07 January, 2021</li>
                                            <li><a href="#" title="View" class="ac-btn ac-btn-primary ac-small" target="_blank">View</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </article>
                        </div>
                    @php } @endphp
                </div>

                <div class="n-mt-lg-80 n-mt-40" data-aos="fade-up">
                    <ul class="pagination justify-content-center align-content-center">
                        <li class="page-item">
                            <a class="page-link" href="#" title="Previous">
                                <i class="n-icon" data-icon="s-pagination"></i>
                            </a>
                        </li>
                        <li class="page-item"><a class="page-link" href="#" title="1">1</a></li>
                        <li class="page-item active"><a class="page-link" href="#" title="2">2</a></li>
                        <li class="page-item"><a class="page-link" href="#" title="3">3</a></li>
                        <li class="page-item"><a class="page-link" href="#" title="4">4</a></li>
                        <li class="page-item"><a class="page-link" href="#" title="5">5</a></li>
                        <li class="page-item">
                            <a class="page-link" href="#" title="Next">
                                <i class="n-icon" data-icon="s-pagination"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>


