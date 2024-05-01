import '../sass/tabler.scss';
import './bootstrap';
import jQuery from 'jquery';
import toastr from 'toastr';
import select2 from "select2"
select2();
window.$ = jQuery;
window.toastr = toastr;
import './tabler-init';

import Swal from 'sweetalert2';
window.Swal = Swal;

$.fn.select2.defaults = {
    width: '100%',
    theme: 'bootstrap-5',
    placeholder: 'সিলেক্ট',
    allowClear: true,
};

import ApexCharts from "apexcharts";
window.ApexCharts = ApexCharts;


