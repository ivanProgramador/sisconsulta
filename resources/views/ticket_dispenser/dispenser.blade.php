<x-layouts.guest-layout subtitle="{{ $subtitle }}">

    <div class="flex flex-col justify-center p-4">

        

        <div class="text-end mb-4">
           [opções]
        </div>
        
        <div class="main-card flex gap-4 w-full">

            <div id="queues" class="flex flex-wrap w-full border-1 border-slate-300 rounded-xl">

                [queues]



            </div>


             <div id="ticket" class="flex w-1/4 h-100 border-1 border-slate-300 rounded-xl p-4" >
                
                   
             </div>

        </div>

    <script>
         
         const url = "{{ route('dispenser.get.bundle.data', ['credential' => $credential ]) }}";
         const queuesContainer = document.querySelector("#queues");

         getBundleData(url);

        async function getBundleData(url) {

            try {
                

                const response = await fetch(url, {
                    method:'POST',
                    headers:{
                        'Content-Type':'application/json'
                    },
                    body: JSON.stringify(
                        {
                            credential:'{{ Crypt::encrypt($credential) }}'
                        }
                    )
               });

                const data = await response.json();

                if (!response.ok) {
                    renderError(data);
                    return;
                }

                // sucesso
                renderQueues(data.queues);

            } catch (error) {
                renderError({ message: 'Erro inesperado na requisição' });
                console.error(error);
            }
        }
 

         function renderError(data){
         queuesContainer.innerHTML =`  
                <div class="flex w-full justify-center mt-10">
                <div class="rounded-xl border border-red-800 text-red-800 text-center p-4">
                    <i class="text-3xl fa-solid fa-triangle-exclamation mb-4"></i>
                    <p>Error: ${data.message}</p>
                </div>
            </div> `;
         }

         function renderQueues(queues){

            const  queuesLayout = queues.length <= 4 ? 'w-1/1' : 'w-1/2';

            queuesContainer.innerHTML = '';

            queues.forEach(queue =>{

                const queueContent = document.createElement('div');
                queueContent.className = `flex gap-2 rounded-xl p-2 ${queuesLayout} hover:bg-black transition-all    `;
                queueContent.style.borderColor = queue.colors.prefix_bg_color;
                queueContent.id = queue.hash_code;

                queueContent.innerHTML = `
                          
                            <div class="text-center font-mono bg-slate-100 rounded-xl p-1"
                            style="background-color:${queue.colors.prefix_bg_color}; color:${queue.colors.prefix_text_color}"
                            >
                                <p class="text-8xl px-4 font-bold text-salte-700">${queue.prefix}</p>
                            </div>
                            

                            <div class="bg-slate-400 w-full rounded-xl p-3"
                                style="background-color:${queue.colors.number_bg_color};  color:${queue.colors.number_text_color}; "
                            >
                                <p class="text-3xl font-bold text-slate-700">${queue.service}</p>
                                <p class="ext-3xl font-bold text-slate-700" >${queue.desk} </p>
                            </div>                
                `;

                //colocando um evento de click 

                queueContent.addEventListener('click',()=>{
                     getTicket(queue.hash_code);
                });





                queuesContainer.appendChild(queueContent);



                 
            });

            async function getTicket(hash_code) {

                const url ="{{ route('dispenser.get.ticket') }}";

                try{
                    
                    
                    const response = await fetch(url,{
                       method:'POST',
                       headers:{
                         'Content-Type': 'application/json',
                         'Accept': 'application/json'
                       },
                       body: JSON.stringify({
                          hash_code: hash_code
                       })
                    });

                    if(!response.ok){
                        const text = await response.json();
                        console.error('Resposta não OK:', text);
                        throw new Error(text);
                    }

                    const data = await response.json();
                    console.log(data);
                    renderTicket(data.ticket);
                    
                   }catch(error){
                      renderTicketError();
                   }
            }

            function renderTicketError(){
                const ticketContainer = document.querySelector("#ticket");
                ticketContainer.innerHTML = `
                <div class="flex flex-col justify-center items-center">
                    <i class="text-6xl text-red-500 fa-solid fa-triangle-exclamation mb-2"></i>
                    <p class="text-center text-red-500">Erro ao obter o ticket</p>
                    <p class="text-center text-red-500 text-sm">Tente novamente ou fale com um atendente</p>
                </div>
                `;
            }

            function renderTicket(ticket){
                const ticketContainer = document.querySelector("#ticket");

                function formatarData(createdAt) {
                    if (!createdAt?.date) return "Data inválida";

                    const iso = createdAt.date.replace(" ", "T");
                    const data = new Date(iso);

                    return data.toLocaleString("pt-BR", {
                        day: "2-digit",
                        month: "2-digit",
                        year: "numeric",
                        hour: "2-digit",
                        minute: "2-digit"
                    });
                }

               

                ticketContainer.innerHTML =`
                <div class="bg-slate-100 rounded-xl w-full text-center">
                    <p class="w-full text-8xl font-bold text-slate-800">${ticket.prefix}</p>
                    <p class="w-full text-7xl font-bold text-slate-800">${ticket.number}</p>
                    <p class="w-full text-2xl font-semibold text-slate-600">${ticket.queue_service}</p>
                    <p class="w-full text-xl font-semibold text-slate-500">${ticket.service_desk}</p>
                    <p class="w-full text-sm text-slate-400 mt-2">Emitido em: ${formatarData(ticket.created_at)}</p>
                </div>
                `;
               
            }


         }

        

    </script>



</x-layouts.guest-layout>
