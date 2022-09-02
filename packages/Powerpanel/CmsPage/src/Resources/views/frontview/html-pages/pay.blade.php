<section class="inner-page-gap pay">
    @include('layouts.share-email-print')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-7">
                <div class="accordion ac-form-wd" id="accordionExample">
                    <div class="-tabs d-flex n-fc-white-500 align-items-center">
                        <div class="-icon"><i class="n-icon" data-icon="s-users"></i></div>
                        <div class="n-ml-15">
                            <span class="n-fs-22">Person Information</span>
                            <div class="n-fs-14 n-lh-120 d-none" id="info1">Kartik Mehta from Netclues!</div>
                        </div>
                        <div class="-edit d-none" id="edit1" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne"><i class="n-icon" data-icon="s-edit"></i></div>
                    </div>
                    <div id="collapseOne" class="-info collapse show" data-parent="#accordionExample">
                        <div class="n-pa-30 n-pa-md-40">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group ac-form-group">
                                        <label class="ac-label" for="firstName">Your Name <span class="star">*</span></label>
                                        <input type="text" class="form-control ac-input" id="firstName" name="firstName" placeholder="" minlength="1" maxlength="255" onpaste="return true;" ondrop="return false;">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group ac-form-group">
                                        <label class="ac-label" for="firstName">Company Name <span class="star">*</span></label>
                                        <input type="text" class="form-control ac-input" id="firstName" name="firstName" placeholder="" minlength="1" maxlength="255" onpaste="return true;" ondrop="return false;">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group ac-form-group">
                                        <label class="ac-label" for="firstName">Your email <span class="star">*</span></label>
                                        <input type="text" class="form-control ac-input" id="firstName" name="firstName" placeholder="" minlength="1" maxlength="255" onpaste="return true;" ondrop="return false;">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group ac-form-group">
                                        <label class="ac-label" for="firstName">Phone No</label>
                                        <input type="text" class="form-control ac-input" id="firstName" name="firstName" placeholder="" minlength="1" maxlength="255" onpaste="return true;" ondrop="return false;">
                                    </div>
                                </div>
                                <div class="col-12 text-right">
                                    <button type="button" class="ac-btn ac-btn-primary ac-small" id="next1" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">Next</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="-tabs d-flex n-fc-white-500 align-items-center">
                        <div class="-icon"><i class="n-icon" data-icon="s-dollar-sign"></i></div>
                        <div class="n-ml-15">
                            <span class="n-fs-22">Payment Information</span>
                            <div class="n-fs-14 n-lh-120 d-none" id="info2">KYD $150 for Invoice Number 1051</div>
                        </div>
                        <div class="-edit d-none collapsed" id="edit2" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo"><i class="n-icon" data-icon="s-edit"></i></div>
                    </div>
                    <div id="collapseTwo" class="-info collapse" data-parent="#accordionExample">
                        <div class="n-pa-30 n-pa-md-40">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group ac-form-group">
                                        <label class="ac-label" for="firstName">Invoice Number <span class="star">*</span></label>
                                        <input type="text" class="form-control ac-input" id="firstName" name="firstName" placeholder="" minlength="1" maxlength="255" onpaste="return true;" ondrop="return false;">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group ac-form-group">
                                        <label class="ac-label" for="firstName">Payable Amount <span class="star">*</span></label>
                                        <input type="text" class="form-control ac-input" id="firstName" name="firstName" placeholder="" minlength="1" maxlength="255" onpaste="return true;" ondrop="return false;">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group ac-form-group ac-active-label">
                                        <label class="ac-label">Payment Currency</label>
                                        <div class="ac-radio-inline">
                                            <label class="ac-radio">
                                                <input type="radio" name="example_1" value="1" checked=""> KYD<span></span>
                                            </label>
                                            <label class="ac-radio">
                                                <input type="radio" name="example_1" value="1"> USD<span></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group ac-form-group ac-active-select">
                                        <label class="ac-label">How would you like to pay? <span class="star">*</span></label>
                                        <select class="selectpicker ac-input" data-width="100%" data-size="5" title="Select Card">
                                            <option>Credit Card</option>
                                            <option>Debit Card</option>
                                        </select>
                                        <span class="ac-note">A 4% processing fee will be added to your transaction.</span>
                                    </div>
                                </div>
                                <div class="col-12 text-right">
                                    <button type="button" class="ac-btn ac-btn-primary ac-small" id="next2" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">Next</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="-tabs d-flex n-fc-white-500 align-items-center">
                        <div class="-icon"><i class="n-icon" data-icon="s-credit-card"></i></div>
                        <div class="n-ml-15">
                            <span class="n-fs-22">Credit Card Information</span>
                            <div class="n-fs-14 n-lh-120 d-none" id="info3">A 4% processing fee will be added to your transaction.</div>
                        </div>
                    </div>
                    <div id="collapseThree" class="-info collapse" data-parent="#accordionExample">
                        <div class="n-pa-30 n-pa-md-40">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group ac-form-group">
                                        <label class="ac-label" for="firstName">Name on Card <span class="star">*</span></label>
                                        <input type="text" class="form-control ac-input" id="firstName" name="firstName" placeholder="" minlength="1" maxlength="255" onpaste="return true;" ondrop="return false;">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group ac-form-group">
                                        <label class="ac-label" for="firstName">Card Number <span class="star">*</span></label>
                                        <input type="text" class="form-control ac-input" id="firstName" name="firstName" placeholder="" minlength="1" maxlength="255" onpaste="return true;" ondrop="return false;">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group ac-form-group ac-active-select">
                                        <label class="ac-label">Expiry Month</label>
                                        <select class="selectpicker ac-input" data-width="100%" data-size="5" title="Select Month">
                                            <option>01-Jan</option>
                                            <option>02-Feb</option>
                                            <option>03-Mar</option>
                                            <option>04-Apr</option>
                                            <option>05-May</option>
                                            <option>06-Jun</option>
                                            <option>07-Jul</option>
                                            <option>08-Aug</option>
                                            <option>09-Sep</option>
                                            <option>10-Oct</option>
                                            <option>11-Nov</option>
                                            <option>12-Dec</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group ac-form-group ac-active-select">
                                        <label class="ac-label">Expiry Year</label>
                                        <select class="selectpicker ac-input" data-width="100%" data-size="5" title="Select Year" data-live-search="true">
                                            <option>2021</option>
                                            <option>2022</option>
                                            <option>2023</option>
                                            <option>2024</option>
                                            <option>2025</option>
                                            <option>2026</option>
                                            <option>2027</option>
                                            <option>2028</option>
                                            <option>2029</option>
                                            <option>2030</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group ac-form-group">
                                        <label class="ac-label" for="firstName">CVV <span class="star">*</span></label>
                                        <input type="text" class="form-control ac-input" id="firstName" name="firstName" placeholder="" minlength="1" maxlength="255" onpaste="return true;" ondrop="return false;">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group ac-form-group n-mb-0">
                                        <label class="ac-label" for="yourMessage">Your Note</label>
                                        <textarea class="form-control ac-textarea" id="yourMessage" name="yourMessage" rows="2" placeholder="" onpaste="return true;" ondrop="return false;"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 n-mt-xl-0 n-mt-25">
                <div class="details">
                    <div class="-title n-pa-15 text-center">
                        <div class="n-fs-22 n-fc-white-500">Your Payment Details</div>
                        <div class="ac-note text-center n-fc-white-500">This payment is in the KYD</div>
                    </div>
                    <div class="-detail n-pa-20">
                        <table>
                            <tr>
                                <td>Payable Amount</td>
                                <td class="text-right">$100</td>
                            </tr>
                            <tr>
                                <td>Processing Fee</td>
                                <td class="text-right">$4</td>
                            </tr>
                            <tr>
                                <td class="-paya">Total to Pay</td>
                                <td class="text-right -paya">$104</td>
                            </tr>
                        </table>
                        <div class="ac-note n-mt-15">I authorize Ofreg to charge the credit card indicated in this form according to the terms outlined above. I certify that I am an authorized user of this credit card and that I will not dispute the payment with my credit card company; so long as the transaction corresponds to the terms indicated in this form.</div>
                        <button class="ac-btn ac-btn-primary btn-block n-mt-15">Pay Now</button>
                        <div class="ac-note text-center n-mt-15"><b>Note:</b> By clicking Pay Now, you agree to our 
                        <a href="{{ url('/privacy-policy') }}" target="_blank" title="Privacy Policy">Privacy Policy</a>.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="inner-page-gap pay-faq n-pt-40 n-pt-lg-80">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-8 text-center" data-aos="fade-up">
                <h2 class="nqtitle">Frequently Asked Questions for the Online Payment</h2>
                <div class="cms n-mt-15">
                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
                </div>
            </div>

            <div class="col-10 n-bgc-white-500 n-mt-40 n-bs-1" data-aos="fade-up">
                <ul class="nqul ac-collapse accordion" id="payaccordion">
                    <li class="-li">
                        <a class="-tabs collapsed" data-toggle="collapse" href="#pay01" aria-expanded="true" aria-controls="pay01" title="What is the meaning of Lorem ipsum?">What is the meaning of Lorem ipsum? <span></span></a>
                        <div id="pay01" class="-info collapse" aria-labelledby="headingOne" data-parent="#payaccordion">
                            <div class="cms">
                                <p>Literally it does not mean anything. It is a sequence of words without a sense of Latin derivation that make up a text also known as filler text, fictitious, blind or placeholder</p>
                                <table>
                                    <tr>
                                        <th>Entry Header 1</th>
                                        <th>Entry Header 2</th>
                                        <th>Entry Header 3</th>
                                        <th>Entry Header 4</th>
                                        <th>Entry Header 5</th>
                                        <th>Entry Header 6</th>
                                        <th>Entry Header 7</th>
                                    </tr>
                                    <tr>
                                        <td>Entry First Line 1</td>
                                        <td>Entry First Line 2</td>
                                        <td>Entry First Line 3</td>
                                        <td>Entry First Line 4</td>
                                        <td>Entry First Line 5</td>
                                        <td>Entry First Line 6</td>
                                        <td>Entry First Line 7</td>
                                    </tr>
                                    <tr>
                                        <td>Entry Line 1</td>
                                        <td>Entry Line 2</td>
                                        <td>Entry Line 3</td>
                                        <td>Entry Line 4</td>
                                        <td>Entry Line 5</td>
                                        <td>Entry Line 6</td>
                                        <td>Entry Line 7</td>
                                    </tr>
                                    <tr>
                                        <td>Entry Line 1</td>
                                        <td>Entry Line 2</td>
                                        <td>Entry Line 3</td>
                                        <td>Entry Line 4</td>
                                        <td>Entry Line 5</td>
                                        <td>Entry Line 6</td>
                                        <td>Entry Line 7</td>
                                    </tr>
                                    <tr>
                                        <td>Entry Last Line 1</td>
                                        <td>Entry Last Line 2</td>
                                        <td>Entry Last Line 3</td>
                                        <td>Entry Last Line 4</td>
                                        <td>Entry Last Line 5</td>
                                        <td>Entry Last Line 6</td>
                                        <td>Entry Last Line 7</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </li>
                    <li class="-li">
                        <a class="-tabs collapsed" data-toggle="collapse" href="#pay02" aria-expanded="true" aria-controls="pay02" title="Why is Lorem Ipsum Dolor used?">Why is Lorem Ipsum Dolor used? <span></span></a>
                        <div id="pay02" class="-info collapse" aria-labelledby="headingOne" data-parent="#payaccordion">
                            <div class="cms">
                                <p>The Lorem Ipsum text is used to fill spaces designated to host texts that have not yet been published. They use programmers, graphic designers, typographers to get a real impression of the digital / advertising / editorial product they are working on.</p>
                            </div>
                        </div>
                    </li>
                    <li class="-li">
                        <a class="-tabs collapsed" data-toggle="collapse" href="#pay03" aria-expanded="true" aria-controls="pay03" title="What is the most used version?">What is the most used version? <span></span></a>
                        <div id="pay03" class="-info collapse" aria-labelledby="headingOne" data-parent="#payaccordion">
                            <div class="cms">
                                <p>Lorem ipsum dolor sit amet, consectetur adipisci elit, sed eiusmod tempor incidunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur. Quis aute iure reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint obcaecat cupiditat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                            </div>
                        </div>
                    </li>
                    <li class="-li">
                        <a class="-tabs collapsed" data-toggle="collapse" href="#pay04" aria-expanded="true" aria-controls="pay04" title="What are the origins of Lorem Ipsum Dolor Sit?">What are the origins of Lorem Ipsum Dolor Sit? <span></span></a>
                        <div id="pay04" class="-info collapse" aria-labelledby="headingOne" data-parent="#payaccordion">
                            <div class="cms">
                                <p>Its origins date back to 45 BC. In fact, his words were randomly extracted from the De finibus bonorum et malorum , a classic of Latin literature written by Cicero over 2000 years ago.</p>
                            </div>
                        </div>
                    </li>
                    <li class="-li">
                        <a class="-tabs collapsed" data-toggle="collapse" href="#pay05" aria-expanded="true" aria-controls="pay05" title="What is the original text of Lorem Ipsum Dolor Sit Amet?">What is the original text of Lorem Ipsum Dolor Sit Amet? <span></span></a>
                        <div id="pay05" class="-info collapse" aria-labelledby="headingOne" data-parent="#payaccordion">
                            <div class="cms">
                                <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam eaque ipsa, quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt, explicabo. Nemo enim ipsam voluptatem, quia voluptas sit, aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos, qui ratione voluptatem sequi nesciunt, neque porro quisquam est, qui dolorem ipsum, quia dolor sit, amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt, ut labore et dolore magnam aliquam quaerat voluptatem.</p>
                                <p>Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit, qui in ea voluptate velit esse, quam nihil molestiae consequatur, vel illum, qui dolorem eum fugiat, quo voluptas nulla pariatur? [33] At vero eos et accusamus et iusto odio dignissimos ducimus, qui blanditiis praesentium voluptatum deleniti atque corrupti, quos dolores et quas molestias excepturi sint, obcaecati cupiditate non provident, similique sunt in culpa, qui officia deserunt mollitia animi, id est laborum et dolorum fuga. Et harum quidem rerum facilis est et expedita distinctio.</p>
                                <blockquote>
                                    <p>Nam libero tempore, cum soluta nobis est eligendi optio, cumque nihil impedit, quo minus id, quod maxime placeat, facere possimus, omnis voluptas assumenda est, omnis dolor repellendus. Temporibus autem quibusdam et aut officiis debitis aut rerum necessitatibus saepe eveniet, ut et voluptates repudiandae sint et molestiae non recusandae. Itaque earum rerum hic tenetur a sapiente delectus, ut aut reiciendis voluptatibus maiores alias consequatur aut perferendis doloribus asperiores repellat.</p>
                                </blockquote>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>

<script>
    $("#next1").on( "click", function() {
        $('#edit1, #info1').removeClass("d-none");
    });
    $("#edit1").on( "click", function() {
        $('#edit1, #edit2, #info1, #info2, #info3').addClass("d-none");
    });
    $("#next2").on( "click", function() {
        $('#edit2, #info2, #info3').removeClass("d-none");
    });
    $("#edit2").on( "click", function() {
        $('#edit2, #info2, #info3').addClass("d-none");
    });
</script>