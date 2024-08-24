@extends('layout')
@section('conm')


<div class="relative">


<div class=" absolute right-1/2 -translate-x-12 top-12 ">
    <button
        class="text-slate-800 hover:text-blue-600 text-sm bg-white hover:bg-slate-100 border border-slate-200 rounded-r-lg font-medium px-4 py-2 inline-flex space-x-1 items-center">
        <svg width="20px" height="20px" viewBox="0 0 1024 1024" class="icon" version="1.1" xmlns="http://www.w3.org/2000/svg" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><path d="M719.8 651.8m-10 0a10 10 0 1 0 20 0 10 10 0 1 0-20 0Z" fill="#E73B37"></path><path d="M512.1 64H172v896h680V385.6L512.1 64z m278.8 324.3h-280v-265l280 265zM808 916H216V108h278.6l0.2 0.2v296.2h312.9l0.2 0.2V916z" fill="#39393A"></path><path d="M280.5 530h325.9v16H280.5z" fill="#39393A"></path><path d="M639.5 530h90.2v16h-90.2z" fill="#E73B37"></path><path d="M403.5 641.8h277v16h-277z" fill="#39393A"></path><path d="M280.6 641.8h91.2v16h-91.2z" fill="#E73B37"></path><path d="M279.9 753.7h326.5v16H279.9z" fill="#39393A"></path><path d="M655.8 753.7h73.9v16h-73.9z" fill="#E73B37"></path></g></svg>

        </span>
        <span class="hidden md:inline-block">حفظ</span>
    </button>
    <button
        class="text-slate-800 hover:text-blue-600 text-sm bg-white hover:bg-slate-100 border rounded-l-lg border-slate-200 font-medium px-4 py-2 inline-flex space-x-1 items-center">
        <span>
            <a href="https://wa.me/+967775376507?text=Hello%20there!" target="_blank"  class="">
                <svg class="" width="20px" height="20px" viewBox="0 0 48 48" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <title>Whatsapp-color</title> <desc>Created with Sketch.</desc> <defs> </defs> <g id="Icons" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"> <g id="Color-" transform="translate(-700.000000, -360.000000)" fill="#67C15E"> <path d="M723.993033,360 C710.762252,360 700,370.765287 700,383.999801 C700,389.248451 701.692661,394.116025 704.570026,398.066947 L701.579605,406.983798 L710.804449,404.035539 C714.598605,406.546975 719.126434,408 724.006967,408 C737.237748,408 748,397.234315 748,384.000199 C748,370.765685 737.237748,360.000398 724.006967,360.000398 L723.993033,360.000398 L723.993033,360 Z M717.29285,372.190836 C716.827488,371.07628 716.474784,371.034071 715.769774,371.005401 C715.529728,370.991464 715.262214,370.977527 714.96564,370.977527 C714.04845,370.977527 713.089462,371.245514 712.511043,371.838033 C711.806033,372.557577 710.056843,374.23638 710.056843,377.679202 C710.056843,381.122023 712.567571,384.451756 712.905944,384.917648 C713.258648,385.382743 717.800808,392.55031 724.853297,395.471492 C730.368379,397.757149 732.00491,397.545307 733.260074,397.27732 C735.093658,396.882308 737.393002,395.527239 737.971421,393.891043 C738.54984,392.25405 738.54984,390.857171 738.380255,390.560912 C738.211068,390.264652 737.745308,390.095816 737.040298,389.742615 C736.335288,389.389811 732.90737,387.696673 732.25849,387.470894 C731.623543,387.231179 731.017259,387.315995 730.537963,387.99333 C729.860819,388.938653 729.198006,389.89831 728.661785,390.476494 C728.238619,390.928051 727.547144,390.984595 726.969123,390.744481 C726.193254,390.420348 724.021298,389.657798 721.340985,387.273388 C719.267356,385.42535 717.856938,383.125756 717.448104,382.434484 C717.038871,381.729275 717.405907,381.319529 717.729948,380.938852 C718.082653,380.501232 718.421026,380.191036 718.77373,379.781688 C719.126434,379.372738 719.323884,379.160897 719.549599,378.681068 C719.789645,378.215575 719.62006,377.735746 719.450874,377.382942 C719.281687,377.030139 717.871269,373.587317 717.29285,372.190836 Z" id="Whatsapp"> </path> </g> </g> </g></svg>
            </a>
        </span>
        <span class="hidden md:inline-block">ارسل</span>
    </button>

</div>
<br>

<div class=" relative border border-gray-300 shadow-sm rounded-lg overflow-hidden max-w-lg mx-auto mt-16">
    <input class=" w-full rounded-md bg-gray-200" value="فضل المطري" disabled>
    <table class="w-full text-sm leading-5">
      <thead class="bg-gray-100">
        <tr>
          <th class="py-3 px-4 text-left font-medium text-gray-600">اسم الصنف </th>
          <th class="py-3 px-4 text-left font-medium text-gray-600">سعر الصنف</th>
          <th class="py-3 px-4 text-left font-medium text-gray-600"> المدفوع</th>
          <th class="py-3 px-4 text-left font-medium text-gray-600"> المتبقي</th>
          <th class="py-3 px-4 text-left font-medium text-gray-600">تاريخ الشراء</th>
        </tr>
      </thead>
      <tbody>
        @for ($i = 0; $i < 5; $i++)

        <tr>
          <td class="py-3 px-4 text-left font-medium text-gray-600">لوح شمسي</td>
          <td class="py-3 px-4 text-left">5000</td>
          <td class="py-3 px-4 text-left font-medium text-gray-600">3000</td>
          <td class="py-3 px-4 text-left">2000</td>
          <td class="py-3 px-4 text-left">2024/9/9</td>
        </tr>
@endfor
      </tbody>
    </table>
  </div>

</div>


@endsection
