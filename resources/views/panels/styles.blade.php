<!-- BEGIN: Vendor CSS-->
@if ($configData['direction'] === 'rtl' && isset($configData['direction']))
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/vendors-rtl.min.css')) }}"/>
@else
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/vendors.min.css')) }}"/>
@endif
<link rel="stylesheet" type="text/css"
      href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<style>
    #toast-container>div {
        opacity: unset;
    }
    #toast-container>.toast-success {
        background-color: #28c76f;
    }

    #toast-container>.toast-error {
        background-color: #e05a63;
    }
</style>


@yield('vendor-style')
<!-- END: Vendor CSS-->

<!-- BEGIN: Theme CSS-->
<link rel="stylesheet" href="{{ asset(mix('css/core.css')) }}"/>
<link rel="stylesheet" href="{{ asset(mix('css/base/themes/dark-layout.css')) }}"/>
<link rel="stylesheet" href="{{ asset(mix('css/base/themes/bordered-layout.css')) }}"/>
<link rel="stylesheet" href="{{ asset(mix('css/base/themes/semi-dark-layout.css')) }}"/>

@php $configData = Helper::applClasses(); @endphp

<!-- BEGIN: Page CSS-->
@if ($configData['mainLayoutType'] === 'horizontal')
    <link rel="stylesheet" href="{{ asset(mix('css/base/core/menu/menu-types/horizontal-menu.css')) }}"/>
@else
    <link rel="stylesheet" href="{{ asset(mix('css/base/core/menu/menu-types/vertical-menu.css')) }}"/>
@endif

{{-- Page Styles --}}
@yield('page-style')

<!-- laravel style -->
<link rel="stylesheet" href="{{ asset(mix('css/overrides.css')) }}"/>

<!-- BEGIN: Custom CSS-->

@if ($configData['direction'] === 'rtl' && isset($configData['direction']))
    <link rel="stylesheet" href="{{ asset(mix('css-rtl/custom-rtl.css')) }}"/>
    <link rel="stylesheet" href="{{ asset(mix('css-rtl/style-rtl.css')) }}"/>

@else
    {{-- user custom styles --}}
    <link rel="stylesheet" href="{{ asset(mix('css/style.css')) }}"/>
@endif
