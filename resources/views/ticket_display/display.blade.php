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
         console.log(queues);
      }

      refreshInterval = setInterval(() => {
          getBundleData(url).then(data=>{
              render(data);
           });
        
      }, queueInterval);
      
     

       
    </script>
</x-layouts.guest-layout>
