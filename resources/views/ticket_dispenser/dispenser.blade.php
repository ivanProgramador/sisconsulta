<x-layouts.guest-layout subtitle="{{ empty($subtitle) ? '' : $subtitle }}">

    <div class="flex flex-col justify-center p-2 h-full">

        <div class="text-end mb-4">
            [opções]
        </div>
        
        
                    <div class="main-card flex gap-4 w-full">

                    <div class="flex flex-wrap w-full border-1 border-slate-300 rounded-xl" id="queues">

                      [Filas]
                        
                    </div>

                    <div class="flex w-1/3 h-100 border-1 border-slate-300 rounded-xl p-2">
                            [preview do ticket]
                    </div>
    



       

            
                
                     
        
        </div>





    </div>

    <script>
       
       //variavel que vai receber a rota 
       const url = "{{ route('dispenser.get.bundle.data') }}";

       const queuesContainer = document.querySelector("#queues");

       getBundleData(url).then(data=>{
          render(data);
       });

       //funcção que será executada de forma assincrona

       async function getBundleData(url){
          
           try{
             
            const response = await fetch(url,[
                 method: 'POST',
                    headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    },
                    body: JSON.stringify(
                        { 
                            credential: '{{ Crypt::encrypt($credential) }}'
                        })
              ]);


              if(!response.ok){
                 throw new Error('Erro ao buscar dados: ' + response.status);
              }
              return await response.json();

            }catch(error){
              render(error);
              return null;
           }
       }


       function render(data){
          
          if(data.status === 'error'){
            
               renderError(data);

           }else{
               
              renderQueues(data.queues);

          }
        }


        function  renderError(data){

            queuesContainer.innerHTML = `
               <div class="flex flex-col items-center justify-center w-full text-red-500 p-4 mt-4">

                  <i class="text-3xl fa-solid fa-triangle-exclamation mb-2 "></i>
                  <p>Erro: ${data.message}</p>
                
               </div>
            `;


        }

        function renderQueues(queues){
             
           //testando a quantidade elementos do array para definir o comportamnto do layout

           const queuesLayout = queues.length <= 4 ? 'w-1/1': 'w-1/2';

           queuesContainer.innerHTML = '';
          
           queues.forEach(queue=>{
              
             const queueContent = document.createElement('a');
             queueContent.className = `flex gap-2 rounded-xl p-2 hover:bg-black ${queuesLayout}`;
             queueContent.style.borderColor = queue.colors.prefix_bg_color;
             queueContent.href='#';

             queueContent.innerHTML =`   
                    <div class="text-center font-mono rounded-xl boder-1 boder-zinc-800 p-1"
                    style="background-color: ${queue.colors.prefix_bg_color}; color:${queue.colors.prefix_text_color}">
                        <p class="text-8xl px-4 font-bold"> ${queue.prefix}</p>
                    </div>
                   
                    <div class="bg-white w-full rounded-xl boder-1 border-zinc-800 p-3"

                        style="background-color:${queue.colors.number_bg_color};  color:${queue.colors.number_text_color} ">

                        <p class="text-3xl font-bold text-slate-700">${queue.service}</p>
                        <p class="ext-3xl font-bold text-slate-700" >${queue.desk } </p>
                    </div>
                `;



             
             queuesContainer.appendChild(queueContent);

             
           });




        }





       





    </script>

</x-layouts.guest-layout>