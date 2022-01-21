$('input').on('change', function () {
  $.ajax(
    {
      method: 'POST',
      url: '/api/panier',
      data: {slug: $(this).attr('name'), quantity: $(this).val()},
    }
  ).done((data) => {
    $(this).closest('tr').find('td:last-child').text(data.price)
  })
})
