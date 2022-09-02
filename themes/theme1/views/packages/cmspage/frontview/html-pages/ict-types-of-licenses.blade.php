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
                    <h2 style="text-align:center;">Listed in the following Tables 1 to 9</h2>
                </div>
                <ul class="nqul ac-collapse accordion" id="ictTypesOfLicenses">
                    <li class="-li">
                        <a class="-tabs" data-toggle="collapse" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">TABLE 1: MAJOR PUBLIC ICT NETWORKS <span></span></a>
                        <div id="collapseOne" class="-info collapse show" data-parent="#ictTypesOfLicenses">
                            <div class="cms">
                                <p style="text-align:center;">(Licensees of these networks are subject to a royalty fee based upon a percentage of annual gross revenue.)</p>
                                <table>
                                    <tr>
                                        <th>Type</th>
                                        <th>Description</th>
                                        <th>Comments or explanatory notes</th>
                                    </tr>
                                    <tr>
                                        <td>A</td>
                                        <td>Fixed wireline</td>
                                        <td>A wireline network providing access to ICT Service(s) to residential and/or business Subscribers.</td>
                                    </tr>
                                    <tr>
                                        <td>B</td>
                                        <td>Fixed wireless</td>
                                        <td>A wireless network (other than Mobile) providing access to ICT Service(s) to residential and/or business Subscribers.</td>
                                    </tr>
                                    <tr>
                                        <td>C</td>
                                        <td>Mobile (cellular)</td>
                                        <td>Mobile networks operating according to international standards known as 2G, 3G, 4G, LTE, 5G, or any other bands designated as such by the Office.</td>
                                    </tr>
                                    <tr>
                                        <td>D1</td>
                                        <td>Fibre optic cable - Domestic</td>
                                        <td>Whether "lit" or "unlit".</td>
                                    </tr>
                                    <tr>
                                        <td>D2</td>
                                        <td>Fibre optic cable - International</td>
                                        <td>Whether "lit" or "unlit".</td>
                                    </tr>
                                    <tr>
                                        <td>E1</td>
                                        <td>Satellite (incl VSAT) - Domestic </td>
                                        <td>-</td>
                                    </tr>
                                    <tr>
                                        <td>E2</td>
                                        <td>Satellite (incl VSAT) International</td>
                                        <td>-</td>
                                    </tr>
                                    <tr>
                                        <td>F</td>
                                        <td>Broadcasts</td>
                                        <td>As used for over-the-air" radio and TV broadcasters.<br>
                                        This includes occasional limited range broadcasts according to the related policies and procedures as set out by the Office and may be set out in any open or class licences issued by the Office</td>
                                    </tr>
                                    <tr>
                                        <td>G</td>
                                        <td>Internet Exchange Point (IXP)</td>
                                        <td>Any infrastructure operated by a Type 16 Service licensee which facilitates or allows Internet Service Providers to exchange traffic between networks, by means of mutual peering agreements. See Note (c) below</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </li>

                    <li class="-li">
                        <a class="-tabs collapsed" data-toggle="collapse" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">TABLE 2: RADIO STATIONS <span></span></a>
                        <div id="collapseTwo" class="-info collapse" data-parent="#ictTypesOfLicenses">
                            <div class="cms">
                                <p style="text-align:center;">(Licensees of these networks are subject to a fixed licence fee, usually annually.)</p>
                                <table>
                                    <tr>
                                        <th>Type</th>
                                        <th>Description</th>
                                        <th>Comments or explanatory notes</th>
                                    </tr>
                                    <tr>
                                        <td>J</td>
                                        <td>Amateur radio station</td>
                                        <td>
                                            Radio transmitting and receiving equipment which;<br>
                                            (a) is used solely for a Person's own use;<br>
                                            (b) operates on radio frequencies to be specified by the Office following consultation, and<br>
                                            (c) is limited in output power to a level to be specified by the Office following consultation.
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>K</td>
                                        <td>Aircraft radio station</td>
                                        <td>For aircraft registered in the Cayman Islands.</td>
                                    </tr>
                                    <tr>
                                        <td>L1</td>
                                        <td>Ship radio station</td>
                                        <td>For vessels of less than 300 gross tons with no MMSI number.</td>
                                    </tr>
                                    <tr>
                                        <td>L2</td>
                                        <td>Ship radio station</td>
                                        <td>For vessels of less than 300 gross tons requiring an MMSI number.</td>
                                    </tr>
                                    <tr>
                                        <td>L3</td>
                                        <td>Ship radio station</td>
                                        <td>For vessels of more than 300 gross tons but less than 1600 gross tons.</td>
                                    </tr>
                                    <tr>
                                        <td>L4</td>
                                        <td>Ship radio station</td>
                                        <td>For vessels of greater than 1,600 gross tons.</td>
                                    </tr>
                                    <tr>
                                        <td>L5</td>
                                        <td>Ship radio station</td>
                                        <td>Coastal vessel</td>
                                    </tr>
                                    <tr>
                                        <td>M</td>
                                        <td>Spacecraft radio station</td>
                                        <td>For spacecraft registered in the Cayman Islands.</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </li>

                    <li class="-li">
                        <a class="-tabs collapsed" data-toggle="collapse" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">TABLE 3: TRANSMITTERS <span></span></a>
                        <div id="collapseThree" class="-info collapse" data-parent="#ictTypesOfLicenses">
                            <div class="cms">
                                <p style="text-align:center;">(Licensees of these networks are subject to a fixed license fee per transmitter, usually annually.)</p>
                                <table>
                                    <tr>
                                        <th>Type</th>
                                        <th>Description</th>
                                        <th>Comments or explanatory notes</th>
                                    </tr>
                                    <tr>
                                        <td>N</td>
                                        <td>Ground to air radio</td>
                                        <td>Radio transmitting equipment used to communicate from the ground to aircraft on the ground and in the air using aviation frequencies.</td>
                                    </tr>
                                    <tr>
                                        <td>O</td>
                                        <td>Marine (Coastal) radio</td>
                                        <td>Base station operating on designated maritime frequencies in the coastal waters of the Cayman Islands.</td>
                                    </tr>
                                    <tr>
                                        <td>P</td>
                                        <td>Land (Mobile) two-way radio</td>
                                        <td>Base station and mobile radio transmitters (including hand-held) operating on designated frequencies in the Cayman Islands.</td>
                                    </tr>
                                    <tr>
                                        <td>Q</td>
                                        <td>Wireless Utilities Metering</td>
                                        <td>Base station and wireless metering equipment at the end-users' premises.</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </li>

                    <li class="-li">
                        <a class="-tabs collapsed" data-toggle="collapse" href="#collapseFour" aria-expanded="false" aria-controls="collapseFour">TABLE 4: SPECTRUM <span></span></a>
                        <div id="collapseFour" class="-info collapse" data-parent="#ictTypesOfLicenses">
                            <div class="cms">
                                <p style="text-align:center;">(Fixed spectrum license fees are to be paid annually.)</p>
                                <table>
                                    <tr>
                                        <th>Type</th>
                                        <th>Description</th>
                                        <th>Comments or explanatory notes</th>
                                    </tr>
                                    <tr>
                                        <td>S</td>
                                        <td>All transmitters/ transponders other than those licensed under Tables 2 or 3, or those otherwise exempted by the Office.</td>
                                        <td>
                                            Each transmission frequency or channel is required to be licensed. This requirement extends to most uses of spectrum whether that use is in connection with a public or private network. For the avoidance of doubt, Licensees of Table 1 are required to obtain the appropriate Type S Licences. Only Licensees of Tables 2 or 3 are exempt from the requirement to obtain a Type S Licence.<br><br>
                                            In addition, certain types of low power radio equipment may be exempted from any licensing requirement, or may be covered by a class licence, if operated in the ISM bands set out in Annex 2.
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </li>

                    <li class="-li">
                        <a class="-tabs collapsed" data-toggle="collapse" href="#collapseFive" aria-expanded="false" aria-controls="collapseFive">TABLE 5: MISCELLANEOUS <span></span></a>
                        <div id="collapseFive" class="-info collapse" data-parent="#ictTypesOfLicenses">
                            <div class="cms">
                                <p style="text-align:center;">(Licensees are subject to a fixed license fee, usually annually.)</p>
                                <table>
                                    <tr>
                                        <th>Type</th>
                                        <th>Description</th>
                                        <th>Comments or explanatory notes</th>
                                    </tr>
                                    <tr>
                                        <td>U</td>
                                        <td>Radio dealer</td>
                                        <td>A licensed dealer in radio transmitters is exempted from obtaining in advance an import licence for each radio that they import for subsequent resale.</td>
                                    </tr>
                                    <tr>
                                        <td>V</td>
                                        <td>Radio Operator</td>
                                        <td>-</td>
                                    </tr>
                                    <tr>
                                        <td>W</td>
                                        <td>Radio Importation</td>
                                        <td>-</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </li>

                    <li class="-li">
                        <a class="-tabs collapsed" data-toggle="collapse" href="#collapseSix" aria-expanded="false" aria-controls="collapseSix">TABLE 6: ICT SERVICES <span></span></a>
                        <div id="collapseSix" class="-info collapse" data-parent="#ictTypesOfLicenses">
                            <div class="cms">
                                <p style="text-align:center;">(Licensees of these services are subject to a royalty fee based upon a percentage of annual gross revenue.)</p>
                                <table>
                                    <tr>
                                        <th>Type</th>
                                        <th>Description</th>
                                        <th>Comments or explanatory notes</th>
                                    </tr>
                                    <tr>
                                        <td>1</td>
                                        <td>Fixed Telephony</td>
                                        <td>Whether utilising fixed wireline or fixed wireless networks. Refer also to paragraph 6a of this Notice for a description of Telephony.</td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>Fall-back International Voice and Data Communications</td>
                                        <td>An applicant for a Type 2 Licence must already hold, or must apply simultaneously for, a Type E2 (International Satellite) Network Licence. For further information, see Note (a).</td>
                                    </tr>
                                    <tr>
                                        <td>3</td>
                                        <td>Mobile Telephony</td>
                                        <td>-</td>
                                    </tr>
                                    <tr>
                                        <td>4</td>
                                        <td>Resale of Telephony</td>
                                        <td>Also includes those services sometimes referred to as calling card services.</td>
                                    </tr>
                                    <tr>
                                        <td>5</td>
                                        <td>Internet Telephony</td>
                                        <td>Voice over the Public Internet (not the use of Voice over Internet Protocol, which falls under Type 1).</td>
                                    </tr>
                                    <tr>
                                        <td>6</td>
                                        <td>Public Service Broadcasting Subscription Broadcasting</td>
                                        <td>-</td>
                                    </tr>
                                    <tr>
                                        <td>7</td>
                                        <td>Subscription Television Broadcasting</td>
                                        <td>Sometimes referred to as “cable broadcasting" or "wireless cable broadcasting". Historically, a Type 7 License was not issued without a Type 6 Licence. This is no longer a requirement.</td>
                                    </tr>
                                    <tr>
                                        <td>8</td>
                                        <td>Sound Broadcasting</td>
                                        <td>Refer to paragraph 6c of this Notice.</td>
                                    </tr>
                                    <tr>
                                        <td>9</td>
                                        <td>Internet Service Provider</td>
                                        <td>-</td>
                                    </tr>
                                    <tr>
                                        <td>10</td>
                                        <td>Information Security Services</td>
                                        <td>Licensing by the Office is optional at the discretion of the applicant. See Note (b) below.</td>
                                    </tr>
                                    <tr>
                                        <td>11</td>
                                        <td>The provision, by lease or otherwise, of ICT infrastructure other than dark fibre to a Licensee.</td>
                                        <td>Includes buried infrastructure such as ducts, overhead infrastructure such as poles, and other structures such as towers, buildings, and similar.</td>
                                    </tr>
                                    <tr>
                                        <td>11a</td>
                                        <td>The provision, by lease or otherwise, of dark fibre to a Licensee.</td>
                                        <td>-</td>
                                    </tr>
                                    <tr>
                                        <td>12</td>
                                        <td>Retail sale of ICT equipment</td>
                                        <td>A Type 12 Licence is available only to Types 1, 3 or 5 Licensees. All others must obtain an appropriate licence from the Cayman Islands Trade and Business Licensing Board. For consistency with the procedures adopted by that Board.</td>
                                    </tr>
                                    <tr>
                                        <td>13</td>
                                        <td>Subscriber Record Directory Service</td>
                                        <td>
                                            The publication of directories derived directly from ICT subscriber records<br><br>
                                            Refer to paragraph 6e ii of this Notice.
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>14</td>
                                        <td>Application Service Provider</td>
                                        <td>-</td>
                                    </tr>
                                    <tr>
                                        <td>15</td>
                                        <td>Resale of Internet Service</td>
                                        <td>Subject to the exemptions listed in paragraphs 6 d (i)(a) and(b). To be considered compliant Reseller must implement minimum security standards to be defined from time to time by the Office.</td>
                                    </tr>
                                    <tr>
                                        <td>16</td>
                                        <td>Internet Peering Service Provider</td>
                                        <td>See Note (c) below.</td>
                                    </tr>
                                </table>

                                <h3>Note</h3>
                                <p>(a) This licence permits a Licensee to offer international voice and data communications to the Licensee's business clients solely for use in emergency situations. An emergency is defined as instances where the client's normal communications service provider(s) is unable to provide its services for a period of such duration that there is a material impact upon the transaction of the client's normal business (e.g. as the result of damage following a hurricane) and the Office has acknowledged in writing the existence of such conditions.</p>
                                <p>(b) Information Security Services may be licensed by the Office on application from Persons who wish to be so licensed, but such licensing is not mandatory. Where a Person wishing to provide Information Security Services makes application to the Office for an ICT Service Licence, the Office will process that application in the same manner and to the same standards as it would process applications for any other type of ICT Service Licence, and the Office may decline to award such Licence.</p>
                                <p>(c) This Licence will not be issued to or held by a person holding, or affiliated with a person holding, a Type 5 or Type 9 Service Licence.</p>
                            </div>
                        </div>
                    </li>

                    <li class="-li">
                        <a class="-tabs collapsed" data-toggle="collapse" href="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">TABLE 7: SERVICE LICENCES ISSUED BY THE GOVERNOR IN CABINET UNDER SECTION 23(3) OF THE LAW, FOR THE <span></span></a>
                        <div id="collapseSeven" class="-info collapse" data-parent="#ictTypesOfLicenses">
                            <div class="cms">
                                <table>
                                    <tr>
                                        <th>Type</th>
                                        <th>Description</th>
                                        <th>Comments or explanatory notes</th>
                                    </tr>
                                    <tr>
                                        <td>100</td>
                                        <td>The provision of off-site ICT disaster recovery and associated services</td>
                                        <td>Applicable only to Licensees operating within and from Cayman Brac or Little Cayman, and as further defined in their Licences.</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </li>

                    <li class="-li">
                        <a class="-tabs collapsed" data-toggle="collapse" href="#collapseEight" aria-expanded="false" aria-controls="collapseEight">TABLE 8: CLASS LICENCE ISSUED BY THE INFORMATION AND COMMUNICATIONS TECHNOLOGY OFFICE UNDER SECTION 23(2) OF THE LAW, FOR FALL-BACK INTERNATIONAL VOICE AND DATA COMMUNICATIONS <span></span></a>
                        <div id="collapseEight" class="-info collapse" data-parent="#ictTypesOfLicenses">
                            <div class="cms">
                                <table>
                                    <tr>
                                        <th>Type</th>
                                        <th>Description</th>
                                        <th>Comments or explanatory notes</th>
                                    </tr>
                                    <tr>
                                        <td>200</td>
                                        <td>permits a Class Licensee to put in place an international satellite Network and associated Services, for the Licensee's exclusive use in emergency situations, so as to provide itself with fall-back international voice and data communications.</td>
                                        <td>
                                            An emergency is defined as instances where the Class Licensee's normal communications service provider(s) is unable to provide its services for a period of such duration that there is a material impact upon the transaction of the client's normal business (e.g. as the result of damage following a hurricane) and the Office has acknowledged in writing the existence of such conditions. Testing restrictions are noted in the actual licence.<br><br>
                                            The rights and obligations normally associated with Licences for Public ICT Networks (Table 2) or Services (Table 6), such as infrastructure sharing and wholesale rates, are not applicable to Type 200 Class Licenses, unless otherwise determined by the Office.
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </li>

                    <li class="-li">
                        <a class="-tabs collapsed" data-toggle="collapse" href="#collapseNine" aria-expanded="false" aria-controls="collapseNine">TABLE 9: OCCASIONAL OR EXPERIMENTAL ICT SERVICES OR NETWORKS <span></span></a>
                        <div id="collapseNine" class="-info collapse" data-parent="#ictTypesOfLicenses">
                            <div class="cms">
                                <table>
                                    <tr>
                                        <th>Type</th>
                                        <th>Description</th>
                                        <th>Notes</th>
                                    </tr>
                                    <tr>
                                        <td>A1</td>
                                        <td>Occasional or Experimental Network</td>
                                        <td><strong> Deployment of any licensable ICT infrastructure for temporary public use associated with research and development or educational purposes.</strong></td>
                                    </tr>
                                    <tr>
                                        <td>A2</td>
                                        <td>Occasional or Experimental Service</td>
                                        <td><strong>Provision of any licensable ICT Service for temporary public use associated with research and development or educational purposes.</strong></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </li>
                </ul>

                <div class="cms">
                    <p style="text-align: right"><strong>J. PAUL. MORGAN</strong> <br>Chief Executive Officer<br>Utility Regulation and Competition Office</p>
                </div>

                {{-- <div class="cms">
                    <h2 style="text-align:center;">Listed in the following Tables 1 to 9</h2>
                    <h2 style="text-align:center;">Table 1: Major Public ICT Networks</h2>
                    <p style="text-align:center;">(Licensees of these networks are subject to a royalty fee based upon a percentage of annual gross revenue.)</p>
                    <table>
                        <tr>
                            <th>Type</th>
                            <th>Description</th>
                            <th>Comments or explanatory notes</th>
                        </tr>
                        <tr>
                            <td>A</td>
                            <td>Fixed wireline</td>
                            <td>A wireline network providing access to ICT Service(s) to residential and/or business Subscribers.</td>
                        </tr>
                        <tr>
                            <td>B</td>
                            <td>Fixed wireless</td>
                            <td>A wireless network (other than Mobile) providing access to ICT Service(s) to residential and/or business Subscribers.</td>
                        </tr>
                        <tr>
                            <td>C</td>
                            <td>Mobile (cellular)</td>
                            <td>Mobile networks operating according to international standards known as 2G, 3G, 4G, LTE, 5G, or any other bands designated as such by the Office.</td>
                        </tr>
                        <tr>
                            <td>D1</td>
                            <td>Fibre optic cable - Domestic</td>
                            <td>Whether "lit" or "unlit".</td>
                        </tr>
                        <tr>
                            <td>D2</td>
                            <td>Fibre optic cable - International</td>
                            <td>Whether "lit" or "unlit".</td>
                        </tr>
                        <tr>
                            <td>E1</td>
                            <td>Satellite (incl VSAT) - Domestic </td>
                            <td>-</td>
                        </tr>
                        <tr>
                            <td>E2</td>
                            <td>Satellite (incl VSAT) International</td>
                            <td>-</td>
                        </tr>
                        <tr>
                            <td>F</td>
                            <td>Broadcasts</td>
                            <td>As used for over-the-air" radio and TV broadcasters.<br>
                            This includes occasional limited range broadcasts according to the related policies and procedures as set out by the Office and may be set out in any open or class licences issued by the Office</td>
                        </tr>
                        <tr>
                            <td>G</td>
                            <td>Internet Exchange Point (IXP)</td>
                            <td>Any infrastructure operated by a Type 16 Service licensee which facilitates or allows Internet Service Providers to exchange traffic between networks, by means of mutual peering agreements. See Note (c) below</td>
                        </tr>
                    </table>

                    <h2 style="text-align:center;">Table 2: Radio Stations</h2>
                    <p style="text-align:center;">(Licensees of these networks are subject to a fixed licence fee, usually annually.)</p>
                    <table>
                        <tr>
                            <th>Type</th>
                            <th>Description</th>
                            <th>Comments or explanatory notes</th>
                        </tr>
                        <tr>
                            <td>J</td>
                            <td>Amateur radio station</td>
                            <td>
                                Radio transmitting and receiving equipment which;<br>
                                (a) is used solely for a Person's own use;<br>
                                (b) operates on radio frequencies to be specified by the Office following consultation, and<br>
                                (c) is limited in output power to a level to be specified by the Office following consultation.
                            </td>
                        </tr>
                        <tr>
                            <td>K</td>
                            <td>Aircraft radio station</td>
                            <td>For aircraft registered in the Cayman Islands.</td>
                        </tr>
                        <tr>
                            <td>L1</td>
                            <td>Ship radio station</td>
                            <td>For vessels of less than 300 gross tons with no MMSI number.</td>
                        </tr>
                        <tr>
                            <td>L2</td>
                            <td>Ship radio station</td>
                            <td>For vessels of less than 300 gross tons requiring an MMSI number.</td>
                        </tr>
                        <tr>
                            <td>L3</td>
                            <td>Ship radio station</td>
                            <td>For vessels of more than 300 gross tons but less than 1600 gross tons.</td>
                        </tr>
                        <tr>
                            <td>L4</td>
                            <td>Ship radio station</td>
                            <td>For vessels of greater than 1,600 gross tons.</td>
                        </tr>
                        <tr>
                            <td>L5</td>
                            <td>Ship radio station</td>
                            <td>Coastal vessel</td>
                        </tr>
                        <tr>
                            <td>M</td>
                            <td>Spacecraft radio station</td>
                            <td>For spacecraft registered in the Cayman Islands.</td>
                        </tr>
                    </table>

                    <h2 style="text-align:center;">Table 3: Transmitters</h2>
                    <p style="text-align:center;">(Licensees of these networks are subject to a fixed license fee per transmitter, usually annually.)</p>
                    <table>
                        <tr>
                            <th>Type</th>
                            <th>Description</th>
                            <th>Comments or explanatory notes</th>
                        </tr>
                        <tr>
                            <td>N</td>
                            <td>Ground to air radio</td>
                            <td>Radio transmitting equipment used to communicate from the ground to aircraft on the ground and in the air using aviation frequencies.</td>
                        </tr>
                        <tr>
                            <td>O</td>
                            <td>Marine (Coastal) radio</td>
                            <td>Base station operating on designated maritime frequencies in the coastal waters of the Cayman Islands.</td>
                        </tr>
                        <tr>
                            <td>P</td>
                            <td>Land (Mobile) two-way radio</td>
                            <td>Base station and mobile radio transmitters (including hand-held) operating on designated frequencies in the Cayman Islands.</td>
                        </tr>
                        <tr>
                            <td>Q</td>
                            <td>Wireless Utilities Metering</td>
                            <td>Base station and wireless metering equipment at the end-users' premises.</td>
                        </tr>
                    </table>

                    <h2 style="text-align:center;">Table 4: Spectrum</h2>
                    <p style="text-align:center;">(Fixed spectrum license fees are to be paid annually.)</p>
                    <table>
                        <tr>
                            <th>Type</th>
                            <th>Description</th>
                            <th>Comments or explanatory notes</th>
                        </tr>
                        <tr>
                            <td>S</td>
                            <td>All transmitters/ transponders other than those licensed under Tables 2 or 3, or those otherwise exempted by the Office.</td>
                            <td>
                                Each transmission frequency or channel is required to be licensed. This requirement extends to most uses of spectrum whether that use is in connection with a public or private network. For the avoidance of doubt, Licensees of Table 1 are required to obtain the appropriate Type S Licences. Only Licensees of Tables 2 or 3 are exempt from the requirement to obtain a Type S Licence.<br><br>
                                In addition, certain types of low power radio equipment may be exempted from any licensing requirement, or may be covered by a class licence, if operated in the ISM bands set out in Annex 2.
                            </td>
                        </tr>
                    </table>

                    <h2 style="text-align:center;">Table 5: Miscellaneous</h2>
                    <p style="text-align:center;">(Licensees are subject to a fixed license fee, usually annually.)</p>
                    <table>
                        <tr>
                            <th>Type</th>
                            <th>Description</th>
                            <th>Comments or explanatory notes</th>
                        </tr>
                        <tr>
                            <td>U</td>
                            <td>Radio dealer</td>
                            <td>A licensed dealer in radio transmitters is exempted from obtaining in advance an import licence for each radio that they import for subsequent resale.</td>
                        </tr>
                        <tr>
                            <td>V</td>
                            <td>Radio Operator</td>
                            <td>-</td>
                        </tr>
                        <tr>
                            <td>W</td>
                            <td>Radio Importation</td>
                            <td>-</td>
                        </tr>
                    </table>

                    <h2 style="text-align:center;">Table 6: ICT Services</h2>
                    <p style="text-align:center;">(Licensees of these services are subject to a royalty fee based upon a percentage of annual gross revenue.)</p>
                    <table>
                        <tr>
                            <th>Type</th>
                            <th>Description</th>
                            <th>Comments or explanatory notes</th>
                        </tr>
                        <tr>
                            <td>1</td>
                            <td>Fixed Telephony</td>
                            <td>Whether utilising fixed wireline or fixed wireless networks. Refer also to paragraph 6a of this Notice for a description of Telephony.</td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Fall-back International Voice and Data Communications</td>
                            <td>An applicant for a Type 2 Licence must already hold, or must apply simultaneously for, a Type E2 (International Satellite) Network Licence. For further information, see Note (a).</td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>Mobile Telephony</td>
                            <td>-</td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>Resale of Telephony</td>
                            <td>Also includes those services sometimes referred to as calling card services.</td>
                        </tr>
                        <tr>
                            <td>5</td>
                            <td>Internet Telephony</td>
                            <td>Voice over the Public Internet (not the use of Voice over Internet Protocol, which falls under Type 1).</td>
                        </tr>
                        <tr>
                            <td>6</td>
                            <td>Public Service Broadcasting Subscription Broadcasting</td>
                            <td>-</td>
                        </tr>
                        <tr>
                            <td>7</td>
                            <td>Subscription Television Broadcasting</td>
                            <td>Sometimes referred to as “cable broadcasting" or "wireless cable broadcasting". Historically, a Type 7 License was not issued without a Type 6 Licence. This is no longer a requirement.</td>
                        </tr>
                        <tr>
                            <td>8</td>
                            <td>Sound Broadcasting</td>
                            <td>Refer to paragraph 6c of this Notice.</td>
                        </tr>
                        <tr>
                            <td>9</td>
                            <td>Internet Service Provider</td>
                            <td>-</td>
                        </tr>
                        <tr>
                            <td>10</td>
                            <td>Information Security Services</td>
                            <td>Licensing by the Office is optional at the discretion of the applicant. See Note (b) below.</td>
                        </tr>
                        <tr>
                            <td>11</td>
                            <td>The provision, by lease or otherwise, of ICT infrastructure other than dark fibre to a Licensee.</td>
                            <td>Includes buried infrastructure such as ducts, overhead infrastructure such as poles, and other structures such as towers, buildings, and similar.</td>
                        </tr>
                        <tr>
                            <td>11a</td>
                            <td>The provision, by lease or otherwise, of dark fibre to a Licensee.</td>
                            <td>-</td>
                        </tr>
                        <tr>
                            <td>12</td>
                            <td>Retail sale of ICT equipment</td>
                            <td>A Type 12 Licence is available only to Types 1, 3 or 5 Licensees. All others must obtain an appropriate licence from the Cayman Islands Trade and Business Licensing Board. For consistency with the procedures adopted by that Board.</td>
                        </tr>
                        <tr>
                            <td>13</td>
                            <td>Subscriber Record Directory Service</td>
                            <td>
                                The publication of directories derived directly from ICT subscriber records<br><br>
                                Refer to paragraph 6e ii of this Notice.
                            </td>
                        </tr>
                        <tr>
                            <td>14</td>
                            <td>Application Service Provider</td>
                            <td>-</td>
                        </tr>
                        <tr>
                            <td>15</td>
                            <td>Resale of Internet Service</td>
                            <td>Subject to the exemptions listed in paragraphs 6 d (i)(a) and(b). To be considered compliant Reseller must implement minimum security standards to be defined from time to time by the Office.</td>
                        </tr>
                        <tr>
                            <td>16</td>
                            <td>Internet Peering Service Provider</td>
                            <td>See Note (c) below.</td>
                        </tr>
                    </table>

                    <h3>Note</h3>
                    <p>(a) This licence permits a Licensee to offer international voice and data communications to the Licensee's business clients solely for use in emergency situations. An emergency is defined as instances where the client's normal communications service provider(s) is unable to provide its services for a period of such duration that there is a material impact upon the transaction of the client's normal business (e.g. as the result of damage following a hurricane) and the Office has acknowledged in writing the existence of such conditions.</p>
                    <p>(b) Information Security Services may be licensed by the Office on application from Persons who wish to be so licensed, but such licensing is not mandatory. Where a Person wishing to provide Information Security Services makes application to the Office for an ICT Service Licence, the Office will process that application in the same manner and to the same standards as it would process applications for any other type of ICT Service Licence, and the Office may decline to award such Licence.</p>
                    <p>(c) This Licence will not be issued to or held by a person holding, or affiliated with a person holding, a Type 5 or Type 9 Service Licence.</p>

                    <h2 style="text-align:center;">Table 7: Service Licences issued by the Governor in Cabinet under Section 23(3) of the Law, for the</h2>
                    <table>
                        <tr>
                            <th>Type</th>
                            <th>Description</th>
                            <th>Comments or explanatory notes</th>
                        </tr>
                        <tr>
                            <td>100</td>
                            <td>The provision of off-site ICT disaster recovery and associated services</td>
                            <td>Applicable only to Licensees operating within and from Cayman Brac or Little Cayman, and as further defined in their Licences.</td>
                        </tr>
                    </table>

                    <h2 style="text-align:center;">Table 8: Class Licence issued by the Information and Communications Technology Office under Section 23(2) of the Law, for Fall-back International Voice and Data Communications</h2>
                    <table>
                        <tr>
                            <th>Type</th>
                            <th>Description</th>
                            <th>Comments or explanatory notes</th>
                        </tr>
                        <tr>
                            <td>200</td>
                            <td>permits a Class Licensee to put in place an international satellite Network and associated Services, for the Licensee's exclusive use in emergency situations, so as to provide itself with fall-back international voice and data communications.</td>
                            <td>
                                An emergency is defined as instances where the Class Licensee's normal communications service provider(s) is unable to provide its services for a period of such duration that there is a material impact upon the transaction of the client's normal business (e.g. as the result of damage following a hurricane) and the Office has acknowledged in writing the existence of such conditions. Testing restrictions are noted in the actual licence.<br><br>
                                The rights and obligations normally associated with Licences for Public ICT Networks (Table 2) or Services (Table 6), such as infrastructure sharing and wholesale rates, are not applicable to Type 200 Class Licenses, unless otherwise determined by the Office.
                            </td>
                        </tr>
                    </table>

                    <h2 style="text-align:center;">Table 9: Occasional or Experimental ICT Services or Networks</h2>
                    <table>
                        <tr>
                            <th>Type</th>
                            <th>Description</th>
                            <th>Notes</th>
                        </tr>
                        <tr>
                            <td>A1</td>
                            <td>Occasional or Experimental Network</td>
                            <td><strong> Deployment of any licensable ICT infrastructure for temporary public use associated with research and development or educational purposes.</strong></td>
                        </tr>
                        <tr>
                            <td>A2</td>
                            <td>Occasional or Experimental Service</td>
                            <td><strong>Provision of any licensable ICT Service for temporary public use associated with research and development or educational purposes.</strong></td>
                        </tr>
                    </table>
                    <p style="text-align: right"><strong>J. PAUL. MORGAN</strong> <br>Chief Executive Officer<br>Utility Regulation and Competition Office</p>
                </div> --}}
            </div>
        </div>
    </div>
</section>


