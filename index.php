<!DOCTYPE html>
<html lang="en">
  <head>  
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="//rawgit.com/botmonster/jquery-bootpag/master/lib/jquery.bootpag.min.js"></script>
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
    <title>Test2</title>
  </head>
  <body>
  
          <div class="col-sm-6">
              <h3>ใบกำกับภาษี</h3>
              <form name="searchText" id="searchText" method="POST" >
                  <input type="text" id="keyword" name="keyword" class="form-control" placeholder="ค้นหา.."/><br>
                  <input type="submit" id="submit" name="submit" value="ค้นหา" class="btn btn-success"/>
              </form>
          </div>
              <div class="text-center">
                <table class="table" id="invoiceTable"> 
                      <thead class="bg-primary text-light">
                          <tr>
                            <th class="text-center">#</th>
                            <th class="text-center">Invoice ID</th>    
                            <th class="text-center">Invoice Number</th>                         
                            <th class="text-center">Name</th>
                            <th class="text-center">Address</th>
                            <th class="text-center">Telephone</th>
                            <th class="text-center">Email</th>
                          </tr>
                      </thead>
                      <tbody id="tbody"></tbody>
                  </table>
                  <tbody id="invoiceBody"> </tbody>
                  <div id="show_pagination"></div>
            </div>
          <!-- การส่งข้อมูลด้วย AJAX เพื่อค้นหา ไปที่ไฟล์ search_result.php-->
    <script>
      $(function(){
// เริ่มต้นให้โหลดข้อมูลทั้งหมดออกมาแสดง โดยเรียกฟังก์ชัน all_users()
        all_users();
        
         $('#searchText').submit(function(e, query = "", page = 1) {
               e.preventDefault();

                let keyword = $("#keyword").val();
                let data = {}; 
                    data["keyword"] = keyword;
                    // data["query"] = query;
                    data["page"] = page;
                let json = JSON.stringify(data);
                // ส่งค่าไป search_result.php ด้วย jQuery Ajax
                $.ajax({
                    url: 'search_result.php',
                    type: 'POST',
                    dataType: 'json',
                    data: { "json": json },
                    success: function(data){
                    console.log(query);
                              // กรณีมีข้อมูล
                            // กำหนดตัวแปรเก็บโครงสร้างแถวของตาราง
                            let trstring ="";
                            // วนลูปข้อมูล JSON ลงตาราง
                            $.each(data, function(key, value){
                                // แสดงค่าลงในตาราง
                                trstring += `
                                    <tr>
                                        <td class="text-center"><button onclick="retrieve(${value.invoice_id});"> + </button></td>
                                        <td class="text-center">${value.invoice_id}</td>
                                        <td class="text-center">${value.invoice_number}</td>
                                        <td class="text-center">${value.name}</td>
                                        <td class="text-center">${value.address}</td>
                                        <td class="text-center">${value.telephone}</td>
                                        <td class="text-center">${value.email}</td>
                                    </tr>

                                   <tr style='display:none' id="invoiceBody${value.invoice_id}">   </tr>
                                    
                                    
                                    `;
                                
                            });
                        $('tbody').html(trstring);
                        createPagination(data.curr, data.page);
                    }
                });
            });
      });
  function all_users(query = "", page = 1) {
                    let data = {};
                    data["query"] = query;
                    data["page"] = page;
                        
                    let json = JSON.stringify(data);

                    $.ajax({ 
                        url: 'all_user.php',
                        type: 'POST', 
                        dataType: 'json',
                        data: {"json": json },
                        success: function(data){
                            
                            // กำหนดตัวแปรเก็บโครงสร้างแถวของตาราง
                                let trstring ="";
                                // วนลูปข้อมูล JSON ลงตาราง
                                $.each(data, function(key, value){
                                    // แสดงค่าลงในตาราง
                                    trstring += `
                                    <id=> 
                                        <td class="text-center"><button onclick="retrieve(${value.invoice_id});"> + </button></td>
                                        <td class="text-center">${value.invoice_id}</td>
                                        <td class="text-center">${value.invoice_number}</td>
                                        <td class="text-center">${value.name}</td>
                                        <td class="text-center">${value.address}</td>
                                        <td class="text-center">${value.telephone}</td>
                                        <td class="text-center">${value.email}</td>        
                                    </id=>
                                    
                                    <tr style='display:none' id="invoiceBody${value.invoice_id}" > </tr>
                                    `;
                        });
                         $('tbody').html(trstring);
                         createPagination(data.curr, data.page);
                    }
                });
          }

            function createPagination(curr, page) {
                    $("#show_pagination").unbind();

                    $('#show_pagination').bootpag({
                    total: page,
                    page: curr,
                    maxVisible: 10,
                    leaps: false,
                    next: 'next',
                    prev: 'prev'
                    }).on('page', function(e, num){

                    let keyword = $("#keyword").val();
                    
                    all_users(keyword, num);

                    });
            }
      // call ฟังชั่นปุ่ม +
                function retrieve(x)
                {
                    let invoice_id = x;
                    let disp = document.getElementById("invoiceBody"+ x);
                    $.ajax({
                    url: 'searchBody.php',
                    type: 'POST',
                    dataType: 'json',
                    data: { "id": invoice_id },
                    success: function(data) {
                        let body = data.res;
                        // กดปุ่มจะโชว์ข้อมูล กดอีกรอบจะซ่อนข้อมูล
                         if (disp.style.display === "none") {
                            disp.style.display = "block";
                            // ถ้าข้อมูลไม่ว่างให้แสดงข้อมูล ถ้าข้อมูลว่างให้แจ้งเตือน"ไม่พบข้อมูล"
                                if(body != ""){                 
                                    
                                let html = "";
                                body.forEach(res => {

                                    html +=  `
                                    
                                    <p><b>Item ID</b>: ${res.item_id}</p>
                                    <p><b>Description</b>: ${res.description}</p>
                                    <p><b>Price</b>: ${res.price}</p>
                                    <p><b>Quantity</b>: ${res.quantity}</p>
                                    <p><b>Vat</b>: ${res.vat}</p>
                                    <p><b>Before Vat</b>: ${res.before_vat}</p>
                                    <p><b>Total</b>: ${res.total}</p>
                                    <p>--------------------</p>
                                    
                                    `;
                                });

                                $("#invoiceBody" + x).html(html);
                            }else{
                                alert('ไม่พบข้อมูล');
                            }
                        } else {
                            disp.style.display = "none";
                        }
                        
                    }
                    
                });
                
}
    </script>  
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>