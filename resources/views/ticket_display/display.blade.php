<x-layouts.guest-layout subtitle="{{ $subtitle }}">

    <div id="dados">
        
    </div>



    <script>

      const url = "{{ route('queues.display.get.bundle.data') }}";

      
      getBundleData(url).then(data=>{
          
          const dados = document.querySelector("#dados");

          dados.innerHTML = '';

          if(data.error){
            dados.innerHTML = 'Aconteceu um erro';
            return;
          }

          data.data.queues.forEach((item)=>{

                 dados.innerHTML +=`<p>Fila: ${item.name} | Serviço: ${item.service_name} | Balcão: ${item.service_desk} </p>`;

              if(item.tickets.length === 0){

                 dados.innerHTML += '<p>Não tem ticket na fila</p>';

              }else{
                 dados.innerHTML += `<p>Ticket chamado: ${item.tickets[0].queue_ticket_number}</p>`;
              }
          });
          

      });

      async function getBundleData(url) {

         try {
          
           const response = await fetch(url,{
             method:"POST",
             headers:{
               'Content-Type':'Application/json',
             },
             body:JSON.stringify(
                {
                  credential:'{{ Crypt::encrypt($credential) }}'
                }
             )
           });

           if(!response.ok){
              throw new Error(response)
           }

           return await response.json();
          
         } catch (error) {

            return {
               error: true,
               message: error.message || 'um erro aconteceu na hora da requisição '
            }
          
         }
        
      }

       
    </script>
</x-layouts.guest-layout>
