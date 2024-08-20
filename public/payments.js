document.getElementById('debit').addEventListener('input',function(){
    var debits= document.getElementById('debit').value;        
    var credits= document.getElementById('credit').value;
    var  purchases="المشتريات", expenses="المصروفات"
     ,bank="البنك",box="الصندوق",supplir="الموردين",cutomers="العملاء"
     ,sales="المبيعات",revenue="الإيرادات",capital="راس المال",
     saleAllowed="مسموحات المبيعات",purchasingAllowed="مسموحات المشتريات";
 if(debits==purchases)
 { 
    if(credits==box)
     {
        expensesBlock();

      } 
        else if(credits==bank)
        {
           expensesBlock();

        }
        else if(credits==supplir)
        {
         supplirsBlock();

        } else {supplirsNone();}

 } 
 else if(debits==expenses)
 {
    if(credits==box)
     {
        expensesBlock();

      }  else if(credits==bank)
        {
           expensesBlock();

        } else {supplirsNone();}
 }
 else if(debits==box)
 {
    if(credits==bank)
        {
           expensesBlock();

        }else if(credits==cutomers)
        {
            customersBlock();

        }else if(credits==sales)
        {
            customersBlock();

        }else if(credits==revenue)
        {
            expensesBlock();

        }else if(credits==capital){expensesBlock();} else {supplirsNone();}


 }
 else if(debits==bank)
 {
     if(credits==box){expensesBlock();}
        else if(credits==cutomers){customersBlock();}
        else if(credits==sales){customersBlock();}
        else if(credits==revenue){expensesBlock();}
        else if(credits==capital){expensesBlock();} else {supplirsNone();}
 }
  else if(debits==supplir)
 {
   if(credits==box){supplirsBlock();}
   else if(credits==bank){supplirsBlock();}
   else if(credits==purchasingAllowed){supplirsBlock();} else {supplirsNone();}
 }
 else if(debits==cutomers)
 {
    if(credits==sales){customersBlock();}
   else  if(credits==saleAllowed){customersBlock();} 
   else  if(credits==box){customersBlock();}  
   else if(credits==bank){customersBlock();}  else {supplirsNone();}
 }
 else {supplirsNone();}
 });
// __________________  credit الدائن المرسل  ______________________
 document.getElementById('credit').addEventListener('input',function(){
     var credits= document.getElementById('credit').value;
     var debits= document.getElementById('debit').value;        
     var  purchases="المشتريات", expenses="المصروفات",bank="البنك",box="الصندوق",supplir="الموردين",cutomers="العملاء",
     sales="المبيعات",revenue="الإيرادات",capital="راس المال",saleAllowed="مسموحات المبيعات",purchasingAllowed="مسموحات المشتريات";

     if(debits==purchases)
     { 
      if(credits==box){expensesBlock();} 
        else if(credits==bank){expensesBlock();}
        else if(credits==supplir){supplirsBlock();}else {supplirsNone();}
     } 
     else if(debits==expenses)
     {
      if(credits==box){expensesBlock();} 
       else if(credits==bank){expensesBlock();}else {supplirsNone();}
     }
     else if(debits==box)
     {
       if(credits==bank){expensesBlock();}
        else if(credits==cutomers){customersBlock();}
        else if(credits==sales){customersBlock();}
        else if(credits==revenue){expensesBlock();}
        else if(credits==capital){expensesBlock();}else {supplirsNone();}
     }
     else if(debits==bank)
      {
       if(credits==box){expensesBlock();}
        else if(credits==cutomers){customersBlock();}
        else if(credits==sales){customersBlock();}
        else if(credits==revenue){expensesBlock();}
        else if(credits==capital)
        {expensesBlock();}else {supplirsNone();}
      } 
     else if(debits==supplir)
      {
              if(credits==box){supplirsBlock();}
         else if(credits==bank){supplirsBlock();}
         else if(credits==purchasingAllowed){supplirsBlock();} else {supplirsNone();}
     }
     else if(debits==cutomers)
     {
             if(credits==sales){customersBlock();}
       else  if(credits==saleAllowed){customersBlock();}
       else  if(credits==box){customersBlock();}
       else  if(credits==bank){customersBlock();} else {supplirsNone();}
     }
 else {supplirsNone();}
});
 function customersBlock(){   // ____________________ data customers _______________________      
 var custome=document.getElementById('supplirs');
 custome.style.display="block";
  document.getElementById('labelName').textContent='اسم العميل';
  document.getElementById('thName').textContent='اسم العميل';
  document.getElementById('labelId').textContent=' رقم العميل';
  document.getElementById('thId').textContent=' رقم العميل';
    }
  function supplirsNone(){       // _____________________ data supplirs _______________________________    
  var supplir=document.getElementById('supplirs');
  supplir.style.display="none";
     }
  function supplirsBlock(){
  var supplir=document.getElementById('supplirs');
  supplir.style.display="block";
  document.getElementById('labelName').innerHTML='اسم المورد';
  document.getElementById('thName').innerHTML='اسم المورد';
  document.getElementById('labelId').innerHTML=' رقم المورد';
  document.getElementById('thId').innerHTML=' رقم المورد';
              }