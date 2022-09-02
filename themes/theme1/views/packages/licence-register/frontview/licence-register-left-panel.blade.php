 <div class="col-xl-3 left-panel">
     <div class="nav-overlay" onclick="closeNav1()"></div>
     <div class="text-right">
         <a href="javascript:void(0)" onclick="openNav1()" id="menu__open1" title="Filter & Menu"
             class="short-menu">Filter & Menu</a>
     </div>
     <div class="menu1" id="menu1">
         <div class="row n-mr-xl-15" data-aos="fade-up">
             <div class="col-12 lpgap">
                 <article>
                     <div class="nqtitle-small lp-title text-uppercase n-mb-25">Sort by Status</div>
                     <div class="form-group ac-form-group n-mb-0">
                         <select class="selectpicker ac-input" data-width="100%" title="Sort by Status"
                             id="statusFilter">
                             <option value="all">All</option>
                             @foreach ($allStatus as $status)
                                 <option value="{{ $status }}">{{ $status }}</option>
                             @endforeach
                         </select>
                     </div>
                 </article>
             </div>
             <div class="col-12 lpgap">
                 <article>
                     <div class="nqtitle-small lp-title text-uppercase n-mb-25">Sort by Service / Network</div>
                     <div class="form-group ac-form-group n-mb-0">
                         <select class="selectpicker ac-input" data-width="100%"
                             title="Sort by Service / Network" id="serviceFilter">
                             <option value="all">All</option>
                             @foreach ($allServices as $service)
                                 <option value="{{ $service->id }}">{{ $service->varTitle }}</option>
                             @endforeach
                         </select>
                     </div>
                 </article>
             </div>
             @if(isset($ictMenu) && !empty($ictMenu))
                <div class="col-12 lpgap">
                    <article>
                        <div class="nqtitle-small lp-title text-uppercase n-mb-25">ICT Information</div>
                        <div class="s-list">
                            {!! $ictMenu !!}
                        </div>
                    </article>
                </div>
            @endif    
         </div>
     </div>
 </div>
