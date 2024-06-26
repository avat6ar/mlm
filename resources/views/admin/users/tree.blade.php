@extends('admin.layouts.app')
@section('panel')
  <div class="mb-4">
    <div class="card custom--card">
      <div class="card-header">
        <h5 class="text-center">@lang('Referrer Link')</h5>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-6">
            <div class="display-block">
              <h5>@lang('Join left') : <span>{{ $user->left }}</span></h5>
            </div>
          </div>

          <div class="col-md-6">
            <div class="display-block">
              <h5>@lang('Join Right') : <span>{{ $user->right }}</span></h5>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="card custom--card mt-4 mb-4">
      <div class="card-body">
        <div class="body genealogy-body genealogy-scroll">
          <div class="genealogy-tree">
            <ul id="tree">
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('script')
  <script>
    "use strict";
    (function($) {
      $('.showDetails').on('click', function() {
        var modal = $('#exampleModalCenter');

        $('.tree_name').text($(this).data('name'));
        $('.tree_url').attr({
          "href": $(this).data('treeurl')
        });
        $('.tree_status').text($(this).data('status'));
        $('.tree_plan').text($(this).data('plan'));
        $('.tree_image').attr({
          "src": $(this).data('image')
        });
        $('.user-details-header').removeClass('Paid');
        $('.user-details-header').removeClass('Free');
        $('.user-details-header').addClass($(this).data('status'));
        $('.tree_ref').text($(this).data('refby'));
        $('.lbv').text($(this).data('lbv'));
        $('.rbv').text($(this).data('rbv'));
        $('.lpaid').text($(this).data('lpaid'));
        $('.rpaid').text($(this).data('rpaid'));
        $('.lfree').text($(this).data('lfree'));
        $('.rfree').text($(this).data('rfree'));
        $('#exampleModalCenter').modal('show');
      });

      $('#copyBoard').click(function() {
        var copyText = document.getElementsByClassName("copyURL");
        copyText = copyText[0];
        copyText.select();
        copyText.setSelectionRange(0, 99999);

        /*For mobile devices*/
        document.execCommand("copy");
        $('.copyText').text('Copied');
        setTimeout(() => {
          $('.copyText').text('Copy');
        }, 2000);
      });
      $('#copyBoard2').click(function() {
        var copyText = document.getElementsByClassName("copyURL2");
        copyText = copyText[0];
        copyText.select();
        copyText.setSelectionRange(0, 99999);

        /*For mobile devices*/
        document.execCommand("copy");
        $('.copyText2').text('Copied');
        setTimeout(() => {
          $('.copyText2').text('Copy');
        }, 2000);
      });
    })(jQuery);
  </script>
@endpush

@push('style')
  <style>
    /*----------------genealogy-scroll----------*/

    /*----------------genealogy-scroll----------*/

    .genealogy-scroll::-webkit-scrollbar {
      width: 5px;
      height: 8px;
    }

    .genealogy-scroll::-webkit-scrollbar-track {
      border-radius: 10px;
      background-color: #e4e4e4;
    }

    .genealogy-scroll::-webkit-scrollbar-thumb {
      background: #212121;
      border-radius: 10px;
      transition: 0.5s;
    }

    .genealogy-scroll::-webkit-scrollbar-thumb:hover {
      background: #d5b14c;
      transition: 0.5s;
    }


    /*----------------genealogy-tree----------*/
    .genealogy-body {
      white-space: nowrap;
      overflow-y: hidden;
      padding: 50px;
      min-height: 250px;
      padding-top: 10px;
    }

    .genealogy-tree ul {
      padding-top: 20px;
      position: relative;
      padding-left: 0px;
      display: flex;
      align-items: flex-start;
    }

    .genealogy-tree li {
      float: left;
      text-align: center;
      list-style-type: none;
      position: relative;
      padding: 20px 5px 0 5px;
    }

    .genealogy-tree li::before,
    .genealogy-tree li::after {
      content: '';
      position: absolute;
      top: 0;
      right: 50%;
      border-top: 2px solid #ccc;
      width: 50%;
      height: 18px;
    }

    .genealogy-tree li::after {
      right: auto;
      left: 50%;
      border-left: 2px solid #ccc;
    }

    .genealogy-tree li:only-child::after,
    .genealogy-tree li:only-child::before {
      display: none;
    }

    .genealogy-tree li:only-child {
      padding-top: 0;
    }

    .genealogy-tree li:first-child::before,
    .genealogy-tree li:last-child::after {
      border: 0 none;
    }

    .genealogy-tree li:last-child::before {
      border-right: 2px solid #ccc;
      border-radius: 0 5px 0 0;
      -webkit-border-radius: 0 5px 0 0;
      -moz-border-radius: 0 5px 0 0;
    }

    .genealogy-tree li:first-child::after {
      border-radius: 5px 0 0 0;
      -webkit-border-radius: 5px 0 0 0;
      -moz-border-radius: 5px 0 0 0;
    }

    .genealogy-tree ul ul::before {
      content: '';
      position: absolute;
      top: 0;
      left: 50%;
      border-left: 2px solid #ccc;
      width: 0;
      height: 20px;
    }

    .genealogy-tree li a {
      text-decoration: none;
      color: #666;
      font-family: arial, verdana, tahoma;
      font-size: 11px;
      display: inline-block;
      border-radius: 5px;
      -webkit-border-radius: 5px;
      -moz-border-radius: 5px;
    }

    .genealogy-tree li a:hover+ul li::after,
    .genealogy-tree li a:hover+ul li::before,
    .genealogy-tree li a:hover+ul::before,
    .genealogy-tree li a:hover+ul ul::before {
      border-color: #fbba00;
    }

    /*--------------memeber-card-design----------*/
    .member-view-box {
      padding: 0px 20px;
      text-align: center;
      border-radius: 4px;
      position: relative;
    }

    .member-image {
      width: 150px;
      position: relative;
    }

    .member-image img {
      width: 60px;
      height: 60px;
      border-radius: 50%;
      z-index: 1;
    }

    .member-details h3 {
      font-size: 16px;
    }

    /* @if (count($tree) >= 6)
    #tree {
      display: block;
      transform: scale(0.9);
    }
    @endif
    */
  </style>
@endpush

@push('script')
  <script>
    $(function() {
      $(".genealogy-tree ul").hide();
      $(".genealogy-tree>ul").show();
      $(".genealogy-tree ul.active").show();
      $(".genealogy-tree li").on("click", function(e) {
        var children = $(this).find("> ul");
        if (children.is(":visible")) children.hide("fast").removeClass("active");
        else children.show("fast").addClass("active");
        e.stopPropagation();
      });
    });

    function appendDom(container, jsonData, isChd) {
      for (var i = 0; i < jsonData.length; i++) {
        let $li = $("<li></li>");
        let $a =
          '<a href="javascript:void(0);"><div class="member-view-box"><div class="member-image"><img src="[[image]]" alt="Member"><div class="member-details"><h3>[[fullName]]</h3></div></div></div></a>';
        $a = $a.replace("[[fullName]]", jsonData[i].username);
        $a = $a.replace("[[image]]", jsonData[i].image);

        $li.append($a);

        if (jsonData[i].children) {
          let $ul = $("<ul></ul>");
          appendDom($ul, jsonData[i].children, true);
          $li.append($ul);
        }

        container.append($li);
      }
    }

    function appendDom(container, jsonData, isChd) {
      for (var i = 0; i < jsonData.length; i++) {
        let $li = $("<li></li>");
        let $a =
          '<a href="javascript:void(0);"><div class="member-view-box"><div class="member-image"><img src="[[image]]" alt="Member"><div class="member-details"><h3>[[fullName]]</h3></div></div></div></a>';
        $a = $a.replace("[[fullName]]", jsonData[i].username);
        $a = $a.replace("[[image]]", jsonData[i].image);

        $li.append($a);

        if (jsonData[i].children) {
          let $ul = $("<ul></ul>");
          appendDom($ul, jsonData[i].children, true);
          $li.append($ul);
        }

        container.append($li);
      }
    }

    var treeData = @json($tree2);

    appendDom($("#tree"), treeData);
  </script>
@endpush

@push('breadcrumb-plugins')
  <x-search-form placeholder="Search by username" />
@endpush
