<x-layouts.guest-layout subtitle="{{ $subtitle }}">
  {{-- port--}}
  
   <div class="flex flex-col justify-center p-4">

        

        <div class="flex justify-end mb-4">
             <p id="access_options" class="w-10 h-10"></p>
             <i id="btn_options" class="fa-solid fa-gear btn-white p-2 !hidden " ></i>
        </div>



        
        <div class="main-card flex gap-4 w-full">

            <div id="queues" class="flex flex-wrap w-full border-1 border-slate-300 rounded-xl">

                [Filas de espera aqui]



            </div>


             

        </div>



        {{-- Modal de configuração --}}

        <div id="modal" class="fixed inset-0 z-50 flex items-center justify-center bg-[#000000aa]" style="display:none;">
              <div class="bg-white rounded-xl shadow-lg p-6 w-200">
                 <p class="title-3">Opções do Apresentador</p>
                 <hr class="border-slate-300 my-4">
                 <div class="flex justify-between gap-4 my-10">

                    <div class="main-card w-full !p-4">
                           <p>Tempo de atualização</p>
                           <p class="cursor-pointer p-2 hover:bg-zinc-200 mb-4" id="refresh_interval_3" > <i class="fa-solid fa-clock-rotate-left"></i> 3 segundos</p>
                           <p class="cursor-pointer p-2 hover:bg-zinc-200 mb-4" id="refresh_interval_5" > <i class="fa-solid fa-clock-rotate-left"></i> 5 segundos</p>
                           <p class="cursor-pointer p-2 hover:bg-zinc-200 mb-4" id="refresh_interval_10"> <i class="fa-solid fa-clock-rotate-left"></i> 10 segundos</p>
                    </div>


                    <div class="main-card w-full flex justify-center items-center text-center !p-4" >
                        <a class="btn" href="{{ route('queues.display.credentials') }}">Voltar as credênciais</a>
                    </div>


                 </div>
                 <div class="flex justify-center">
                     <button id="close_modal" class="btn w-40" >Fechar</button>
                 </div>

              </div>
        </div>

  {{--end port--}}

    



    <script>

      /*mudanças */
      let queueInterval = 5000;
      let refreshInterval = 0;
      const url = "{{ route('queues.display.get.bundle.data') }}";
      const queuesContainer = document.querySelector("#queues");

      /*=====================CONTROLE DO MODAL ====================================*/

        //acessando opçoes 
            document.querySelector("#access_options").addEventListener('dblclick',(event)=>{
                
                const btn_options = document.querySelector("#btn_options");
                btn_options.classList.remove("!hidden");
                event.target.classList.add("!hidden");

                setTimeout(()=>{
                    btn_options.classList.add("!hidden");
                    event.target.classList.remove("!hidden");
                },3000);

            });

        //abrindo opçoes
            document.querySelector("#btn_options").addEventListener('click',()=>{
                document.querySelector("#modal").style.display = "flex";
            });
            
        //fechando modal 
            document.querySelector("#close_modal").addEventListener('click',()=>{
                document.querySelector("#modal").style.display = "none";
            });

        //configurando o tempo de carregamento 
            document.querySelectorAll('[id^="refresh_interval_"]').forEach(element=>{
                element.addEventListener('click',(event)=>{
                    const interval = parseInt(event.target.id.split('_').pop());
                    queueInterval = interval * 1000;

                    //resetando o tempo original do intervalo
                    clearInterval(refreshInterval);
                    refreshInterval = setInterval(()=>{
                          getBundleData(url).then(data=>{
                          renderQueues(data);
                    });
                        
                    },queueInterval);

                    
                });
            });

      /*==============================================================*/


      getBundleData(url).then(data=>{
         render(data);
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

           render(error);
          
         }
        
      }

      function render(data){
         
        //verificando se o data trouxe um erro
        if(data.status === 'error'){
           //mostrar erro 
           renderError(data);
            
        }else{
          //apresentar
          renderQueues(data.data.queues);

        } 




      }

      function renderError(){
         console.log(data);
      }

      function renderQueues(queues){
         const queuesLayout = queues.length <= 4 ? 'w-1/1':'w1/2';
         const queuesContainer.innerHTML = '';

         queues.forEach(queue =>{

            const colors = JSON.parse(queue.queue_colors);
            colors.bg_ticket = '#000000';
            colors.text_ticket ='#ffffffff';

            //identificando filas sem tickets ou inativas
            //aplicando cores padrão e atributos adicionais 

            if(queue.status !== 'active' || queue.tickets.length === 0){

                let bg_color = '#eeeeee';
                let text_color = '#cccccc';
                colors.prefix_bg_color   =  bg_color;
                colors.prefix_text_color =  text_color;
                colors.number_bg_color   =  bg_color;
                colors.number_text_color =  text_color;
                colors.bg_ticket         =  bg_color;
                colors.text_ticket       =  text_color;
            }

            const queueContent = document.createElement('div');
             
            queueContent.className = `flex ${queuesLayout} gap-2 rounded-xl p-2`;
            queueContent.innerHTML =`
               <div class="text-center font-mono rounded-xl border-1 border-zinc-800 p-1"
                         
               >
                 
               </div>
            `;

           
            queuesContainer.appendChild(queueContent);

             

         });


      }

      refreshInterval = setInterval(() => {
          getBundleData(url).then(data=>{
              render(data);
           });
        
      }, queueInterval);
      
     

       
    </script>
</x-layouts.guest-layout>
