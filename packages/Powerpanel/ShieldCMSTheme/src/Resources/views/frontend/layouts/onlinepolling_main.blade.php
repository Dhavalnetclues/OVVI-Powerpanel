@if(Config::get('Constant.DEFAULT_ONLINEPOLLINGFORM') == "Y")
@if(isset($_COOKIE['PollingCookies'])) 
@php $pollingcookiedata = \App\PollingMaster::getPollingCookiesData($_COOKIE['PollingCookies']); @endphp
@else
@php  $pollingcookiedata='0'; @endphp
@endif
@if($pollingcookiedata == 0)
<div data-toggle="modal" data-target="#Modal_onlinepolling" class="feedback_icon">
    <i title="feedback" class="fa fa-thumbs-o-up" aria-hidden="true"></i>
</div>
@endif

<div id="Modal_onlinepolling" class="modal email_modal feedback_model fade" role="dialog" tabindex="-1">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title text-center">Online Polling</h4>
            </div>
            <div class="modal-body">
                {!! Form::open(['method' => 'post','class'=>'','action' => 'PollingMasterController@store','id'=>'polling_form']) !!}
                <div class="row">
                    <div class="col-sm-12">
                        <?php
                        $y = 1;
                        $onlinepollingArr = '';
                        $onlinepollingCatArr = \App\OnlinePollingCategory::getRecordcatListing();
                        $fkpollingid = "";
                        $coupolling = 1;
                        $countonline = 1;
                        foreach ($onlinepollingCatArr as $key => $Catvalue) {
                            $checked = "checked";
                            $onlinepollingArrdata = \App\OnlinePolling::getOnlinePollingCatData($Catvalue->id);
                            if ($countonline > 1) {
                                $style = "display:none;";
                            } else {
                                $style = "";
                            }
                            if (isset($onlinepollingArrdata) && count($onlinepollingArrdata) > 0) {
                                ?>
                                <div id="onlinepollin_{{$coupolling}}" style="{{$style}}" class="onlinepollin_all">
                                    <h4><?php echo $Catvalue->varTitle; ?></h4><br/>
                                    <?php
                                    foreach ($onlinepollingArrdata as $key => $onlinepollingArr) {
                                        if ((isset($onlinepollingArr['varTitle']) && !empty($onlinepollingArr['varTitle'])) && (isset($onlinepollingArr['id']) && !empty($onlinepollingArr['id']))) {

                                            $fkpollingid .= $onlinepollingArr['id'] . ',';
                                            ?>

                                            <div class="form-group">
                                                <label class="label-title">{{ $onlinepollingArr['varTitle'] }}</label>
                                                <p class="ac-mt-xs-5">
                                                    <span><input type="radio" name="polling_{{ $onlinepollingArr['id'] }}" id="Y" value="Y"> <label for="Y">Yes</label></span>&nbsp;
                                                    <span><input type="radio" name="polling_{{ $onlinepollingArr['id'] }}"  id="N" value="N"  {{$checked}} > <label for="N">No</label></span>
                                                </p>
                                            </div>
                                            <?php
                                        }
                                    }
                                    ?>
                                </div>
                                <?php
                                $coupolling++;
                                $countonline++;
                            }
                        }
                        ?>
                        {{ Form::hidden('fkpolling', $fkpollingid) }}
                        <div class="form_nxt_prev">
                            <ul class="next_prev">
                                <li class="prev" id="1" value="1"><i class="fa fa-angle-left"></i> <span>Previous</span></li>
                                <li class="next" id="1" value="1"><span>Next</span> <i class="fa fa-angle-right" ></i></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <?php
                        if (isset($countonline)) {
                            if ($countonline > 1) {
                                $buttonCount = 'display:none';
                                $newxCount = '';
                            } else {
                                $newxCount = 'display:none';
                                $buttonCount = '';
                            }
                        } else {
                            $buttonCount = '';
                        }
                        ?>
                        <div class="captcha_contact" style="{{$buttonCount}}">
                            <div class="captcha_div" >
                                <div class="g-recaptcha" data-sitekey="{{Config::get('Constant.GOOGLE_CAPCHA_KEY')}}"></div>
                            </div>
                            @if ($errors->has('g-recaptcha-response'))
                            <span class="help-block">
                                {{ $errors->first('g-recaptcha-response') }}
                            </span>
                            @endif
                            <button type="submit"  class="ac-btn-primary btn btn-more" title="Submit">Submit</button>

                        </div>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        if ($('ul.next_prev li.prev').val() == $('ul.next_prev li.prev').val()) {
            $('ul.next_prev li.prev').hide();
        }
        $(".next").click(function () {
            var val = $(this).val();
            if (val == 1) {
                var preVal = val;
                var nextVal = val + 1;
            } else {
                var preVal = val;
                var nextVal = val + 1;
            }
            $('ul.next_prev li.prev').attr('value', preVal);
            $('ul.next_prev li.prev').attr('id', preVal);
            $('ul.next_prev li.next').attr('id', nextVal);
            $('ul.next_prev li.next').attr('value', nextVal);
            $('div.onlinepollin_all').each(function (index, value) {
                if ($('div.onlinepollin_all').length >= nextVal) {
                    var index = index + 1;
                    if (nextVal == index) {
                        $('#onlinepollin_' + nextVal).show();
                    } else {
                        $('#onlinepollin_' + index).hide();
                    }
                    if ($('div.onlinepollin_all').length == nextVal) {
                        $('ul.next_prev li.next').hide();
                        $('ul.next_prev li.prev').show();
                        $('button.submitbutton').show();
                        $('.captcha_contact').show();
                    } else {
                        $('ul.next_prev li.next').show();
                        $('ul.next_prev li.prev').show();
                        $('button.submitbutton').hide();
                        $('.captcha_contact').hide();
                    }
                }
            });
        });

        $(".prev").click(function () {
            var val = $(this).val();
            if (val == 1) {
                var preVal = val;
                var nextVal = val;
            } else {
                var preVal = val - 1;
                var nextVal = $('ul.next_prev li.next').val() - (1);
            }
            $('ul.next_prev li.prev').attr('value', preVal);
            $('ul.next_prev li.prev').attr('id', preVal);
            $('ul.next_prev li.next').attr('id', nextVal);
            $('ul.next_prev li.next').attr('value', nextVal);
            $('ul.next_prev li.next').show();
            $('div.onlinepollin_all').each(function (index, value) {
                if ($('div.onlinepollin_all').length >= preVal) {
                    var index = index + 1;
                    if (nextVal == index) {
                        $('#onlinepollin_' + nextVal).show();
                    } else {
                        $('#onlinepollin_' + index).hide();
                    }
                    if ($('ul.next_prev li.prev').val() == $('ul.next_prev li.next').val()) {
                        $('ul.next_prev li.prev').hide();
                        $('button.submitbutton').hide();
                        $('.captcha_contact').hide();
                    } else {
                        $('ul.next_prev li.next').show();
                        $('ul.next_prev li.prev').show();
                        $('button.submitbutton').show();
                        $('.captcha_contact').show();
                    }
                } else {
                    $('div.onlinepollin_all').each(function (index, value) {
                        var index = index + 1;
                        if (index == '1') {
                            $('#onlinepollin_1').show();
                        } else {
                            $('#onlinepollin_' + index).hide();
                        }
                    });
                    $('ul.next_prev li.prev').attr('value', '1');
                    $('ul.next_prev li.prev').attr('id', '1');
                    $('ul.next_prev li.next').attr('id', '1');
                    $('ul.next_prev li.next').attr('value', '1');
                    if ($('ul.next_prev li.prev').val() == $('ul.next_prev li.next').val()) {
                        $('ul.next_prev li.prev').hide();
                        $('button.submitbutton').hide();
                        $('.captcha_contact').hide();
                    } else {
                        $('ul.next_prev li.next').show();
                        $('ul.next_prev li.prev').show();
                        $('button.submitbutton').show();
                        $('.captcha_contact').show();
                    }
                }
            });
        });
    });
</script>
@endif
