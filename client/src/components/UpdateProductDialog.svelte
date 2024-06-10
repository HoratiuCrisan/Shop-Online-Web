<script>
    import { writable } from 'svelte/store';
    import { updateProductById } from '../api/products';
	  import { goto } from '$app/navigation';
    

    export let showUpdateDialog;
    export let product;

    const showDeleteSuccessDialog = writable(false);
    const text = "Product has been updated successfully!";
  
    // Function to close the update dialog
    function closeDialog() {
        showUpdateDialog.set(false);
    }
  
    // Function to handle form submission
    async function updateProduct(event) {
      event.preventDefault();
  
      // Fetch the form data
      const formData = new FormData(event.target);
  
      // Create an updated product object
      const updatedProduct = {
        ...product,
        name: formData.get('name'),
        category: formData.get('category'),
        quantity: parseInt(formData.get('quantity'), 10),
        description: formData.get('description'),
        photoUrl: formData.get('photoUrl'),
        price: parseFloat(formData.get('price')),
        discount: parseFloat(formData.get('discount'))
      };
  
      // Send the updated product to the server
      try {
        const response = await updateProductById(updatedProduct, product.id);

        if (response) {
            console.log(response);
            showDeleteSuccessDialog.set(true);
            goto('/');
        }
      } catch (error) {
        console.log("Error at sending data to the server: " + error);
      }

    }
  </script>
  
  {#if $showUpdateDialog}
    <div class="fixed inset-0 flex items-center justify-center z-50 mt-4">
      <div class="bg-gray-800 bg-opacity-75 absolute inset-0 overflow-y-auto"></div>
      <div class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full max-h-full">
        <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4 overflow-y-auto">
          <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Update Product</h3>
          <form on:submit|preventDefault={updateProduct}>
            {#each ['name', 'category', 'quantity', 'description', 'photoUrl', 'price', 'discount'] as field}
              <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for={field}>
                  {field.charAt(0).toUpperCase() + field.slice(1)}
                </label>
                {#if field === 'description'}
                  <textarea
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    id={field}
                    name={field}
                    required
                  >{product[field]}</textarea>
                {:else}
                  <input
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    id={field}
                    name={field}
                    type={field === 'quantity' || field === 'price' || field === 'discount' ? 'number' : 'text'}
                    step={field === 'price' || field === 'discount' ? '0.01' : undefined}
                    value={product[field]}
                    required
                  />
                {/if}
              </div>
            {/each}
            <div class="flex items-center justify-between">
              <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                Update
              </button>
              <button class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="button" on:click={closeDialog}>
                Cancel
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
{/if}