;(function ($) {
  'use strict'

  var datatableInit = function () {
    $('#datatable-default').dataTable({
      order: [[0, 'asc']],
    })
  }

  $(function () {
    datatableInit()
  })
}.apply(this, [jQuery]))
