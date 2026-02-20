<x-layouts.guest-layout subtitle="{{ $subtitle }}">

    [Painel de filas]



    <script>

      const url = "{{ route('queues.display.get.bundle.data') }}";

      console.log("{{ $credential }}");

      getBundleData(url).then(data=>{
        console.log(data);
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
                  credential:'{{$credential}}'
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
               message: error.message || 'um erro aconteceu na hra da requisição '
            }
          
         }
        
      }

       
    </script>
</x-layouts.guest-layout>
