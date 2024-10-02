   // منع السلوك الافتراضي لزر السهم
   $(document).on('keydown', function(event) {
    if (event.key === "ArrowRight" || event.key === "ArrowLeft") {
        var currentIndex = $('.input-field').index(document.activeElement);

        if (event.key === "ArrowRight") {
            $('.input-field').eq(currentIndex + 1).focus(); // نقل التركيز إلى الحقل التالي
        } else if (event.key === "ArrowLeft") {
            $('.input-field').eq(currentIndex - 1).focus(); // نقل التركيز إلى الحقل السابق
        }
    }
});

$('.inputSale').on('keydown', function(e) {
  var inputs = $('.inputSale');
  var currentIndex = inputs.index(this);

  // السهم السفلي (Down Arrow)
  if (e.which === 40) {
      e.preventDefault();
      if (currentIndex + 1 < inputs.length) {
          inputs.eq(currentIndex + 1).focus();
      }
  }

  // السهم العلوي (Up Arrow)
  if (e.which === 38) {
      e.preventDefault();
      if (currentIndex - 1 >= 0) {
          inputs.eq(currentIndex - 1).focus();
      }
  }
});


function toggleLoading(state) {
  if (state) {
      $('#submitButton').prop('disabled', true).text('جارٍ الحفظ...');
  } else {
      $('#submitButton').prop('disabled', false).text('حفظ القيد');
  }
}
