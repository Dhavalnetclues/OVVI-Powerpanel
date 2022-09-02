<section class="inner-page-gap">
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
                        <div class="col-12 lpgap">
                            <article >
                                <div class="nqtitle-small lp-title text-uppercase n-mb-25">Sort by Company</div>
                                <div class="form-group ac-form-group n-mb-0">
                                    <select class="selectpicker ac-input" data-width="100%" title="Sort by Company">
                                        <option>Cable and Wireless (C.I.) Ltd (T/A FLOW)</option>
                                        <option>Digicel Cayman Limited</option>
                                        <option>Infinity Broadband</option>
                                        <option>WestTel Limited</option>
                                        <option>TeleCayman Limited</option>
                                        <option>Reserved</option></select>
                                    </select>
                                </div>
                            </article>
                        </div>
                        <div class="col-12 lpgap">
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
                    <p>This Table is maintained and published by the ICT Authority as a service to licensees and the general public. It has no legal standing, but rather summarises information contained in individual licences and the TelCordia database. Whilst the ICT Authority will use its best endeavours to keep this Table up-to-date, it provides no guarantee and strongly recommends that users refer to the individual licences prior to making any decisions.</p>

                    <table>
                        <tr>
                            <th>NXX</th>
                            <th>Company</th>
                            <th>Service</th>
                            <th>Notes</th>
                        </tr>
                        <tr>
                            <td>345-222</td>
                            <td>Cable and Wireless (C.I.) Ltd (T/A FLOW)</td>
                            <td>Fixed</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>345-232</td>
                            <td>Infinity Broadband</td>
                            <td>Fixed</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>345-233</td>
                            <td>Infinity Broadband</td>
                            <td>Fixed</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>345-244</td>
                            <td>Cable and Wireless (C.I.) Ltd (T/A FLOW)</td>
                            <td>Fixed</td>
                            <td>Government Central Office Codes</td>
                        </tr>
                        <tr>
                            <td>345-266</td>
                            <td>Cable and Wireless (C.I.) Ltd (T/A FLOW)</td>
                            <td>Fixed</td>
                            <td>*6328 used for Easy Access Internet (Premium)</td>
                        </tr>
                        <tr>
                            <td>345-321</td>
                            <td>Digicel Cayman Limited</td>
                            <td>Mobile - GSM</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>345-322</td>
                            <td>Digicel Cayman Limited</td>
                            <td>Mobile - GSM</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>345-323</td>
                            <td>Digicel Cayman Limited</td>
                            <td>Mobile - GSM</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>345-324</td>
                            <td>Digicel Cayman Limited</td>
                            <td>Mobile</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>345-325</td>
                            <td>Digicel Cayman Limited</td>
                            <td>Mobile - GSM</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>345-326</td>
                            <td>Digicel Cayman Limited</td>
                            <td>Mobile - GSM Post Paid</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>345-327</td>
                            <td>Digicel Cayman Limited</td>
                            <td>Mobile - GSM Pre Paid</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>345-328</td>
                            <td>Digicel Cayman Limited</td>
                            <td>Mobile - GSM</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>345-329</td>
                            <td>Digicel Cayman Limited</td>
                            <td>Mobile - GSM</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>345-333</td>
                            <td>Infinity Broadband</td>
                            <td>Fixed</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>345-420</td>
                            <td>Digicel Cayman Limited</td>
                            <td>Mobile - GSM</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>345-421</td>
                            <td>Digicel Cayman Limited</td>
                            <td>Mobile - GSM</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>345-422</td>
                            <td>Digicel Cayman Limited</td>
                            <td>Mobile - GSM</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>345-423</td>
                            <td>Digicel Cayman Limited</td>
                            <td>Mobile - GSM</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>345-424</td>
                            <td>Digicel Cayman Limited</td>
                            <td>Mobile - GSM</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>345-444</td>
                            <td>Cable and Wireless (C.I.) Ltd (T/A FLOW)</td>
                            <td>Fixed</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>345-516</td>
                            <td>Digicel Cayman Limited</td>
                            <td>Mobile - GSM</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>345-517</td>
                            <td>Digicel Cayman Limited</td>
                            <td>Mobile - GSM</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>345-525</td>
                            <td>Digicel Cayman Limited</td>
                            <td>Mobile - GSM</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>345-526</td>
                            <td>Digicel Cayman Limited</td>
                            <td>Mobile - GSM</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>345-527</td>
                            <td>Digicel Cayman Limited</td>
                            <td>Mobile - GSM</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>345-546</td>
                            <td>Digicel Cayman Limited</td>
                            <td>Mobile - GSM</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>345-547</td>
                            <td>Digicel Cayman Limited</td>
                            <td>Mobile - GSM</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>345-548</td>
                            <td>Digicel Cayman Limited</td>
                            <td>Mobile - GSM</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>345-549</td>
                            <td>Digicel Cayman Limited</td>
                            <td>Mobile - GSM</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>345-550</td>
                            <td>Digicel Cayman Limited</td>
                            <td>Mobile - GSM</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>345-623</td>
                            <td>Digicel Cayman Limited</td>
                            <td>Fixed</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>345-638</td>
                            <td>Cable and Wireless (C.I.) Ltd (T/A FLOW)</td>
                            <td>Fixed</td>
                            <td>*7873 and *4638 used for Interent Service</td>
                        </tr>
                        <tr>
                            <td>345-640</td>
                            <td>Digicel Cayman Limited</td>
                            <td>Fixed</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>345-649</td>
                            <td>Digicel Cayman Limited</td>
                            <td>Fixed</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>345-730</td>
                            <td>Cable and Wireless (C.I.) Ltd (T/A FLOW)</td>
                            <td>Fixed</td>
                            <td>Cable and Wireless internal use only</td>
                        </tr>
                        <tr>
                            <td>345-743</td>
                            <td>WestTel Limited</td>
                            <td>Fixed</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>345-745</td>
                            <td>WestTel Limited</td>
                            <td>Fixed</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>345-746</td>
                            <td>WestTel Limited</td>
                            <td>Fixed</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>345-747</td>
                            <td>Cable and Wireless (C.I.) Ltd (T/A FLOW)</td>
                            <td>Fixed</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>345-749</td>
                            <td>WestTel Limited</td>
                            <td>Fixed</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>345-766</td>
                            <td>TeleCayman Limited</td>
                            <td>Fixed</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>345-767</td>
                            <td>Reserved</td>
                            <td>Blocked</td>
                            <td>Reserved in connection with LNP</td>
                        </tr>
                        <tr>
                            <td>345-768</td>
                            <td>TeleCayman Limited</td>
                            <td>Fixed</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>345-769</td>
                            <td>TeleCayman Limited</td>
                            <td>Fixed</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>345-777</td>
                            <td>Cable and Wireless (C.I.) Ltd (T/A FLOW)</td>
                            <td>Fixed</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>345-800</td>
                            <td>Cable and Wireless (C.I.) Ltd (T/A FLOW)</td>
                            <td>Fixed</td>
                            <td>National only</td>
                        </tr>
                        <tr>
                            <td>345-814</td>
                            <td>Cable and Wireless (C.I.) Ltd (T/A FLOW)</td>
                            <td>Fixed</td>
                            <td>Direct Inward Dial</td>
                        </tr>
                        <tr>
                            <td>345-815</td>
                            <td>Cable and Wireless (C.I.) Ltd (T/A FLOW)</td>
                            <td>Fixed</td>
                            <td>Direct Inward Dial</td>
                        </tr>
                        <tr>
                            <td>345-825</td>
                            <td>TeleCayman Limited</td>
                            <td>Fixed</td>
                            <td>Direct Inward Dial</td>
                        </tr>
                        <tr>
                            <td>345-848</td>
                            <td>Cable and Wireless (C.I.) Ltd (T/A FLOW)</td>
                            <td>Fixed</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>345-849</td>
                            <td>Cable and Wireless (C.I.) Ltd (T/A FLOW)</td>
                            <td>Fixed</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>345-888</td>
                            <td>Cable and Wireless (C.I.) Ltd (T/A FLOW)</td>
                            <td>Fixed</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>345-914</td>
                            <td>Cable and Wireless (C.I.) Ltd (T/A FLOW)</td>
                            <td>Fixed</td>
                            <td>Direct Inward Dial</td>
                        </tr>
                        <tr>
                            <td>345-916</td>
                            <td>Cable and Wireless (C.I.) Ltd (T/A FLOW)</td>
                            <td>Mobile - Post Pd. TDMA</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>345-917</td>
                            <td>Cable and Wireless (C.I.) Ltd (T/A FLOW)</td>
                            <td>Mobile - Pre Pd. TDMA</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>345-919</td>
                            <td>Cable and Wireless (C.I.) Ltd (T/A FLOW)</td>
                            <td>Mobile</td>
                            <td>Temp. Location Directory Numbe</td>
                        </tr>
                        <tr>
                            <td>345-922</td>
                            <td>Cable and Wireless (C.I.) Ltd (T/A FLOW)</td>
                            <td>Mobile - Pre Pd.</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>345-923</td>
                            <td>Cable and Wireless (C.I.) Ltd (T/A FLOW)</td>
                            <td>Mobile - Pre Pd.</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>345-924</td>
                            <td>Cable and Wireless (C.I.) Ltd (T/A FLOW)</td>
                            <td>Mobile - Post Pd. GSM</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>345-925</td>
                            <td>Cable and Wireless (C.I.) Ltd (T/A FLOW)</td>
                            <td>Mobile - Post Pd. GSM</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>345-926</td>
                            <td>Cable and Wireless (C.I.) Ltd (T/A FLOW)</td>
                            <td>Mobile - Post Pd. TDMA</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>345-927</td>
                            <td>Cable and Wireless (C.I.) Ltd (T/A FLOW)</td>
                            <td>Mobile - Pre Pd. TDMA</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>345-928</td>
                            <td>Cable and Wireless (C.I.) Ltd (T/A FLOW)</td>
                            <td>Mobile - Pre Pd. TDMA</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>345-929</td>
                            <td>Cable and Wireless (C.I.) Ltd (T/A FLOW)</td>
                            <td>Mobile - Pre Pd. GSM</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>345-930</td>
                            <td>Cable and Wireless (C.I.) Ltd (T/A FLOW)</td>
                            <td>Internal</td>
                            <td>Cable and Wireless internal use only</td>
                        </tr>
                        <tr>
                            <td>345-936</td>
                            <td>Cable and Wireless (C.I.) Ltd (T/A FLOW)</td>
                            <td>Mobile - Post Pd.</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>345-937</td>
                            <td>Cable and Wireless (C.I.) Ltd (T/A FLOW)</td>
                            <td>Mobile - Post Pd.</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>345-938</td>
                            <td>Cable and Wireless (C.I.) Ltd (T/A FLOW)</td>
                            <td>Mobile - Pre Pd. TDMA</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>345-939</td>
                            <td>Cable and Wireless (C.I.) Ltd (T/A FLOW)</td>
                            <td>Mobile - Pre Pd. GSM</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>345-940</td>
                            <td>Cable and Wireless (C.I.) Ltd (T/A FLOW)</td>
                            <td>Fixed</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>345-943</td>
                            <td>Cable and Wireless (C.I.) Ltd (T/A FLOW)</td>
                            <td>Fixed</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>345-943</td>
                            <td>Cable and Wireless (C.I.) Ltd (T/A FLOW)</td>
                            <td>Fixed</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>345-945</td>
                            <td>Cable and Wireless (C.I.) Ltd (T/A FLOW)</td>
                            <td>Fixed</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>345-946</td>
                            <td>Cable and Wireless (C.I.) Ltd (T/A FLOW)</td>
                            <td>Fixed</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>345-947</td>
                            <td>Cable and Wireless (C.I.) Ltd (T/A FLOW)</td>
                            <td>Fixed</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>345-948</td>
                            <td>Cable and Wireless (C.I.) Ltd (T/A FLOW)</td>
                            <td>Fixed</td>
                            <td>Little Cayman and Cayman Brac Paging</td>
                        </tr>
                        <tr>
                            <td>345-949</td>
                            <td>Cable and Wireless (C.I.) Ltd (T/A FLOW)</td>
                            <td>Fixed</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>345-976</td>
                            <td>Cable and Wireless (C.I.) Ltd (T/A FLOW)</td>
                            <td>Premium</td>
                            <td>*4638 (INET) Internet Access Open</td>
                        </tr>
                        <tr>
                            <td>345-990</td>
                            <td>Cable and Wireless (C.I.) Ltd (T/A FLOW)</td>
                            <td>Mobile</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>345-995</td>
                            <td>Cable and Wireless (C.I.) Ltd (T/A FLOW)</td>
                            <td>Non- Dialable</td>
                            <td>National (Stop Line) Allocation</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>