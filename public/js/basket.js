$('input').on('change', function () {
  $.ajax(
    {
      method: 'POST',
      url: '/api/panier',
      data: {slug: $(this).attr('name'), quantity: $(this).val()},
    }
  ).done((data) => {
    $(this).closest('tr').find('td:last-child').text(data.price);

    $('#ht').text(data.ht);
    $('#promotion').text('-' + data.promotion);
    $('#shippingCosts').text(data.shippingCosts);
    $('#vat').text(data.vat);
    $('#ttc').text(data.ttc);
  })
})
