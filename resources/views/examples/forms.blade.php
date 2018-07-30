@extends('layouts.default')

@push('stylesheets')
    <!-- select2 -->
    <link href="{{asset('assets/css/select2.min.css')}}" rel="stylesheet">
    <!-- switchery -->
    <link rel="stylesheet" href="{{asset('assets/css/switchery.min.css')}}" />
@endpush

@section('main_container')

<div class="">
    <div class="page-title">
        <div class="title_left">
            <h3>Form Validation</h3>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Form validation <small>sub title</small></h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li style="float: right;">
                            <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                        </li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <form class="form-horizontal form-label-left" novalidate>
                        <div class="item form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Name <span class="required">*</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input id="name" class="form-control col-md-7 col-xs-12" data-validate-length-range="6" data-validate-words="2" name="name" placeholder="both name(s) e.g Jon Doe" required="required" type="text">
                            </div>
                        </div>
                        <div class="item form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Email <span class="required">*</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="email" id="email" name="email" required="required" class="form-control col-md-7 col-xs-12">
                            </div>
                        </div>
                        <div class="item form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Confirm Email <span class="required">*</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="email" id="email2" name="confirm_email" data-validate-linked="email" required="required" class="form-control col-md-7 col-xs-12">
                            </div>
                        </div>
                        <div class="item form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="number">Number <span class="required">*</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="number" id="number" name="number" required="required" data-validate-minmax="10,100" class="form-control col-md-7 col-xs-12">
                            </div>
                        </div>
                        <div class="item form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="website">Website URL <span class="required">*</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="url" id="website" name="website" required="required" placeholder="www.website.com" class="form-control col-md-7 col-xs-12">
                            </div>
                        </div>
                        <div class="item form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="occupation">Occupation <span class="required">*</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input id="occupation" type="text" name="occupation" data-validate-length-range="5,20" class="optional form-control col-md-7 col-xs-12">
                            </div>
                        </div>
                        <div class="item form-group">
                            <label for="password" class="control-label col-md-3">Password</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input id="password" type="password" name="password" data-validate-length="6,8" class="form-control col-md-7 col-xs-12" required="required">
                            </div>
                        </div>
                        <div class="item form-group">
                            <label for="password2" class="control-label col-md-3 col-sm-3 col-xs-12">Repeat Password</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input id="password2" type="password" name="password2" data-validate-linked="password" class="form-control col-md-7 col-xs-12" required="required">
                            </div>
                        </div>
                        <div class="item form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="telephone">Telephone <span class="required">*</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="tel" id="telephone" name="phone" required="required" data-validate-length-range="8,20" class="form-control col-md-7 col-xs-12">
                            </div>
                        </div>
                        <div class="item form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="textarea">Textarea <span class="required">*</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <textarea id="textarea" required="required" name="textarea" class="form-control col-md-7 col-xs-12"></textarea>
                            </div>
                        </div>
                        <div class="ln_solid"></div>
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-3">
                                <button type="submit" class="btn btn-primary">Cancel</button>
                                <button id="send" type="submit" class="btn btn-success">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                  <div class="x_title">
                      <h2>Form Basic Elements <small>sub title</small></h2>
                      <ul class="nav navbar-right panel_toolbox">
                          <li style="float: right;">
                              <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                          </li>
                      </ul>
                      <div class="clearfix"></div>
                  </div>
              <div class="x_content">
                <br />
                <form class="form-horizontal form-label-left">

                  <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Default Input</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                      <input type="text" class="form-control" placeholder="Default Input">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Disabled Input </label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                      <input type="text" class="form-control" disabled="disabled" placeholder="Disabled Input">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Read-Only Input</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                      <input type="text" class="form-control" readonly tabindex="-1" placeholder="Read-Only Input">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Date Of Birth <span class="required">*</span>
                    </label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                      <textarea class="form-control" rows="3" placeholder='rows="3"'></textarea>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Password</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                      <input type="password" class="form-control" value="passwordonetwo">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">AutoComplete</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                      <input type="text" name="country" id="autocomplete-custom-append" class="form-control col-md-10" style="float: left;" />
                      <div id="autocomplete-container" style="position: relative; float: left; width: 400px; margin: 10px;"></div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Select</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                      <select class="form-control">
                        <option>Choose option</option>
                        <option>Option one</option>
                        <option>Option two</option>
                        <option>Option three</option>
                        <option>Option four</option>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Select Custom</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                      <select class="select2_single form-control" tabindex="-1">
                        <option></option>
                        <option value="AK">Alaska</option>
                        <option value="HI">Hawaii</option>
                        <option value="CA">California</option>
                        <option value="NV">Nevada</option>
                        <option value="OR">Oregon</option>
                        <option value="WA">Washington</option>
                        <option value="AZ">Arizona</option>
                        <option value="CO">Colorado</option>
                        <option value="ID">Idaho</option>
                        <option value="MT">Montana</option>
                        <option value="NE">Nebraska</option>
                        <option value="NM">New Mexico</option>
                        <option value="ND">North Dakota</option>
                        <option value="UT">Utah</option>
                        <option value="WY">Wyoming</option>
                        <option value="AR">Arkansas</option>
                        <option value="IL">Illinois</option>
                        <option value="IA">Iowa</option>
                        <option value="KS">Kansas</option>
                        <option value="KY">Kentucky</option>
                        <option value="LA">Louisiana</option>
                        <option value="MN">Minnesota</option>
                        <option value="MS">Mississippi</option>
                        <option value="MO">Missouri</option>
                        <option value="OK">Oklahoma</option>
                        <option value="SD">South Dakota</option>
                        <option value="TX">Texas</option>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Select Grouped</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                      <select class="select2_group form-control">
                        <optgroup label="Alaskan/Hawaiian Time Zone">
                          <option value="AK">Alaska</option>
                          <option value="HI">Hawaii</option>
                        </optgroup>
                        <optgroup label="Pacific Time Zone">
                          <option value="CA">California</option>
                          <option value="NV">Nevada</option>
                          <option value="OR">Oregon</option>
                          <option value="WA">Washington</option>
                        </optgroup>
                        <optgroup label="Mountain Time Zone">
                          <option value="AZ">Arizona</option>
                          <option value="CO">Colorado</option>
                          <option value="ID">Idaho</option>
                          <option value="MT">Montana</option>
                          <option value="NE">Nebraska</option>
                          <option value="NM">New Mexico</option>
                          <option value="ND">North Dakota</option>
                          <option value="UT">Utah</option>
                          <option value="WY">Wyoming</option>
                        </optgroup>
                        <optgroup label="Central Time Zone">
                          <option value="AL">Alabama</option>
                          <option value="AR">Arkansas</option>
                          <option value="IL">Illinois</option>
                          <option value="IA">Iowa</option>
                          <option value="KS">Kansas</option>
                          <option value="KY">Kentucky</option>
                          <option value="LA">Louisiana</option>
                          <option value="MN">Minnesota</option>
                          <option value="MS">Mississippi</option>
                          <option value="MO">Missouri</option>
                          <option value="OK">Oklahoma</option>
                          <option value="SD">South Dakota</option>
                          <option value="TX">Texas</option>
                          <option value="TN">Tennessee</option>
                          <option value="WI">Wisconsin</option>
                        </optgroup>
                        <optgroup label="Eastern Time Zone">
                          <option value="CT">Connecticut</option>
                          <option value="DE">Delaware</option>
                          <option value="FL">Florida</option>
                          <option value="GA">Georgia</option>
                          <option value="IN">Indiana</option>
                          <option value="ME">Maine</option>
                          <option value="MD">Maryland</option>
                          <option value="MA">Massachusetts</option>
                          <option value="MI">Michigan</option>
                          <option value="NH">New Hampshire</option>
                          <option value="NJ">New Jersey</option>
                          <option value="NY">New York</option>
                          <option value="NC">North Carolina</option>
                          <option value="OH">Ohio</option>
                          <option value="PA">Pennsylvania</option>
                          <option value="RI">Rhode Island</option>
                          <option value="SC">South Carolina</option>
                          <option value="VT">Vermont</option>
                          <option value="VA">Virginia</option>
                          <option value="WV">West Virginia</option>
                        </optgroup>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Select Multiple</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                      <select class="select2_multiple form-control" multiple="multiple">
                        <option>Choose option</option>
                        <option>Option one</option>
                        <option>Option two</option>
                        <option>Option three</option>
                        <option>Option four</option>
                        <option>Option five</option>
                        <option>Option six</option>
                      </select>
                    </div>
                  </div>

                  <div class="control-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Input Tags</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                      <input id="tags_1" type="text" class="tags form-control" value="social, adverts, sales" />
                      <div id="suggestions-container" style="position: relative; float: left; width: 250px; margin: 10px;"></div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 col-sm-3 col-xs-12 control-label">Checkboxes and radios
                      <br>
                      <small class="text-navy">Normal Bootstrap elements</small>
                    </label>

                    <div class="col-md-9 col-sm-9 col-xs-12">
                      <div class="checkbox">
                        <label>
                          <input type="checkbox" value=""> Option one. select more than one options
                        </label>
                      </div>
                      <div class="checkbox">
                        <label>
                          <input type="checkbox" value=""> Option two. select more than one options
                        </label>
                      </div>
                      <div class="radio">
                        <label>
                          <input type="radio" checked="" value="option1" id="optionsRadios1" name="optionsRadios"> Option one. only select one option
                        </label>
                      </div>
                      <div class="radio">
                        <label>
                          <input type="radio" value="option2" id="optionsRadios2" name="optionsRadios"> Option two. only select one option
                        </label>
                      </div>
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="col-md-3 col-sm-3 col-xs-12 control-label">Checkboxes and radios
                      <br>
                      <small class="text-navy">Normal Bootstrap elements</small>
                    </label>

                    <div class="col-md-9 col-sm-9 col-xs-12">
                      <div class="checkbox">
                        <label>
                          <input type="checkbox" class="flat" checked="checked"> Checked
                        </label>
                      </div>
                      <div class="checkbox">
                        <label>
                          <input type="checkbox" class="flat"> Unchecked
                        </label>
                      </div>
                      <div class="checkbox">
                        <label>
                          <input type="checkbox" class="flat" disabled="disabled"> Disabled
                        </label>
                      </div>
                      <div class="checkbox">
                        <label>
                          <input type="checkbox" class="flat" disabled="disabled" checked="checked"> Disabled & checked
                        </label>
                      </div>
                      <div class="radio">
                        <label>
                          <input type="radio" class="flat" checked name="iCheck"> Checked
                        </label>
                      </div>
                      <div class="radio">
                        <label>
                          <input type="radio" class="flat" name="iCheck"> Unchecked
                        </label>
                      </div>
                      <div class="radio">
                        <label>
                          <input type="radio" class="flat" name="iCheck" disabled="disabled"> Disabled
                        </label>
                      </div>
                      <div class="radio">
                        <label>
                          <input type="radio" class="flat" name="iCheck3" disabled="disabled" checked> Disabled & Checked
                        </label>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Switch</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                      <div class="">
                        <label>
                          <input type="checkbox" class="js-switch" checked /> Checked
                        </label>
                      </div>
                      <div class="">
                        <label>
                          <input type="checkbox" class="js-switch" /> Unchecked
                        </label>
                      </div>
                      <div class="">
                        <label>
                          <input type="checkbox" class="js-switch" disabled="disabled" /> Disabled
                        </label>
                      </div>
                      <div class="">
                        <label>
                          <input type="checkbox" class="js-switch" disabled="disabled" checked="checked" /> Disabled Checked
                        </label>
                      </div>
                    </div>
                  </div>


                  <div class="ln_solid"></div>
                  <div class="form-group">
                    <div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
                      <button type="submit" class="btn btn-primary">Cancel</button>
                      <button type="submit" class="btn btn-success">Submit</button>
                    </div>
                  </div>

                </form>
              </div>
          </div>
        </div>
        <!-- form input mask -->
        <div class="col-md-12 col-sm-12 col-xs-12">
          <div class="x_panel">
            <div class="x_title">
              <h2>Input Mask</h2>
              <ul class="nav navbar-right panel_toolbox">
                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                <li class="dropdown">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                  <ul class="dropdown-menu" role="menu">
                    <li><a href="#">Settings 1</a></li>
                    <li><a href="#">Settings 2</a></li>
                  </ul>
                </li>
                <li><a class="close-link"><i class="fa fa-close"></i></a></li>
              </ul>
              <div class="clearfix"></div>
            </div>
            <div class="x_content">
              <br />
              <form class="form-horizontal form-label-left">
                <div class="form-group">
                  <label class="control-label col-md-3 col-sm-3 col-xs-3">Date Mask</label>
                  <div class="col-md-9 col-sm-9 col-xs-9">
                    <input type="text" class="form-control" data-inputmask="'mask': '99/99/9999'">
                    <span class="fa fa-user form-control-feedback right" aria-hidden="true"></span>
                  </div>
                </div>
                <div class="form-group">
                  <label class="control-label col-md-3 col-sm-3 col-xs-3">Phone mask</label>
                  <div class="col-md-9 col-sm-9 col-xs-9">
                    <input type="text" class="form-control" data-inputmask="'mask' : '(999) 999-9999'">
                    <span class="fa fa-user form-control-feedback right" aria-hidden="true"></span>
                  </div>
                </div>
                <div class="form-group">
                  <label class="control-label col-md-3 col-sm-3 col-xs-3">Custom Mask</label>
                  <div class="col-md-9 col-sm-9 col-xs-9">
                    <input type="text" class="form-control" data-inputmask="'mask': '99-999999'">
                    <span class="fa fa-user form-control-feedback right" aria-hidden="true"></span>
                  </div>
                </div>
                <div class="form-group">
                  <label class="control-label col-md-3 col-sm-3 col-xs-3">Serial Number</label>
                  <div class="col-md-9 col-sm-9 col-xs-9">
                    <input type="text" class="form-control" data-inputmask="'mask' : '****-****-****-****-****-***'">
                    <span class="fa fa-user form-control-feedback right" aria-hidden="true"></span>
                  </div>
                </div>
                <div class="form-group">
                  <label class="control-label col-md-3 col-sm-3 col-xs-3">TaxID Mask</label>
                  <div class="col-md-9 col-sm-9 col-xs-9">
                    <input type="text" class="form-control" data-inputmask="'mask' : '99-99999999'">
                    <span class="fa fa-user form-control-feedback right" aria-hidden="true"></span>
                  </div>
                </div>
                <div class="form-group">
                  <label class="control-label col-md-3 col-sm-3 col-xs-3">Credit Card Mask</label>
                  <div class="col-md-9 col-sm-9 col-xs-9">
                    <input type="text" class="form-control" data-inputmask="'mask' : '9999-9999-9999-9999'">
                    <span class="fa fa-user form-control-feedback right" aria-hidden="true"></span>
                  </div>
                </div>
                
                <div class="ln_solid"></div>
                <div class="form-group">
                  <div class="col-md-9 col-md-offset-3">
                    <button type="submit" class="btn btn-primary">Cancel</button>
                    <button type="submit" class="btn btn-success">Submit</button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
        <!-- /form input mask -->
        </div>
    </div>
</div>

@endsection

@push('scripts')
    <!-- Form Validation -->
    <script src="{{asset('assets/js/validator.js')}}"></script>
    <!-- switchery -->
    <script src="{{asset('assets/js/switchery.min.js')}}"></script>
    <!-- select2 -->
    <script src="{{asset('assets/js/select2.full.min.js')}}"></script>
    <!-- select2 -->
    <script>
    $(document).ready(function() {
      $(".select2_single").select2({
        placeholder: "Select a state",
        allowClear: true
      });
      $(".select2_group").select2({});
      $(".select2_multiple").select2({
        maximumSelectionLength: 4,
        placeholder: "With Max Selection limit 4",
        allowClear: true
      });
    });
    </script>
    <script>
      $(document).ready(function() {
        $(":input").inputmask();
      });
    </script>
    <script>
        // initialize the validator function
        validator.message['date'] = 'not a real date';

        // validate a field on "blur" event, a 'select' on 'change' event & a '.reuired' classed multifield on 'keyup':
        $('form')
            .on('blur', 'input[required], input.optional, select.required', validator.checkField)
            .on('change', 'select.required', validator.checkField)
            .on('keypress', 'input[required][pattern]', validator.keypress);

        $('.multi.required')
            .on('keyup blur', 'input', function(){
                validator.checkField.apply( $(this).siblings().last()[0] );
            });

        // bind the validation to the form submit event
        //$('#send').click('submit');//.prop('disabled', true);

        $('form').submit(function(e){
            e.preventDefault();
            var submit = true;

            // Validate the form using generic validaing
            if( !validator.checkAll( $(this) ) ){
                submit = false;
            }

            if( submit )
                this.submit();

            return false;
        });
    </script>
    <!-- /select2 -->
@endpush
