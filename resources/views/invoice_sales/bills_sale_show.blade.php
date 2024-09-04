


<div class="" x-data="invoices()" >
    <div class="container w-full">

{{--  --}}
        <div class=" border-2 border-black rounded-b-lg my-2 ">
            <div class="bg-gray-200 p-8 rounded-lg  flex justify-between w-full ">
                <div class="text-right">
                    <h2 class="text-xl font-bold mb-2">الريعاني للمواد البناء</h2>
                    <p>مواد بناء <strong>-</strong> ادوات كهربائية <strong>-</strong> دهانات</p>
                    <p> الصباحة السوق الاعلئ بعد سوق القات </p>
                    <p> 772020232-77774633-123456789</p>
                </div>
                <div class="flex items-center justify-center">
                    <div class="w-24 h-20 bg-gray-300 flex items-center justify-center translate-x-10 ">
                        <img class="" src="{{url('img/bnaa.png')}}" alt="">
                    </div>
                </div>
                <div class="text-left">
                    <h2 class="text-xl font-bold mb-2">Company Name</h2>
                    <p>To constract - Elcetric - Funtret</p>
                    <p>Address: 123 Example St</p>
                    <p>Phone: 123456789</p>
                </div>
            </div>
        </div>


        <div class="w-full p-3 bg-gray-100 border-black border-2 rounded-lg  my-2 text-center font-bold">
            <p >فاتورة مبيعات</p>
        </div>

        <div class=" ">
            <table class=" bg-gray-100 w-full text-center  border-black border-2">
                <tbody class="">
                    <tr>
                        <td class="border-black border-2 p-2">التاريخ</td>
                        <td class="border-black border-2 p-2">{{ \Carbon\Carbon::now()->format('Y/m/d') }}</td>
                        <td class="border-black border-2 p-2">رقم الفاتورة</td>
                        <td class="border-black border-2 p-2">2000</td>
                    </tr>
                    <tr>
                        <td class="border-black border-2 p-2">رقم المرجع</td>
                        <td class="border-black border-2 p-2">2000</td>
                        <td class="border-black border-2 p-2">عملة الفاتورة</td>
                        <td class="border-black border-2 p-2">ريال يمني</td>
                    </tr>
                    <tr>
                        <td class="border-black border-2 p-2">العميل</td>
                        <td class="border-black border-2 p-2" colspan="3">فضل عبده حسين المطري</td>
                    </tr>
                </tbody>
            </table>
        </div>
        {{--  --}}
    </div>
</div>






<script>

    // function invoices() {
    //     return {
    //         printInvoice() {
    //             var printContents = this.$refs.printTemplate.innerHTML;
    //             var originalContents = document.body.innerHTML;

    //             document.body.innerHTML = printContents;
    //             window.print();
    //             document.body.innerHTML = originalContents;
    //         }
    //     }
    // }
</script>

