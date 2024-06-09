<script lang="ts">
    import type Product from "../../../interfaces/products";
    import { createProduct,deleteProduct } from "../../../api/products";
    let name = '';
    let description = '';
    let category = '';
    let price = '';
    let photoUrl = '';
    let quantity = 0;
    let selectedFile = null;

    function handleFileUpload(event) {
        selectedFile = event.target.files[0];
        const reader = new FileReader();
        reader.onload = () => {
        if (typeof reader.result === 'string') {
            photoUrl = reader.result;
        }
        };
        reader.readAsDataURL(selectedFile);
    }

    const handleSubmit = async () => {
        const product: Product = {
            name: name,
            description: description,
            category: category,
            price: Number(price),
            photoUrl: photoUrl,
            quantity: quantity,
            discount: 0,
            id: 0
        }

        const response = await createProduct(product);

        console.log(response);
    }
    
</script>

<div class="w-full bg-gray-100 h-screen mt-20">
    <h1 class="text-center text-lg font-bold my-4">Create new product</h1>
    <form on:submit|preventDefault={handleSubmit} class="w-2/3 bg-white rounded-md mx-auto p-4">
        <div class="mb-4">
          <label class="block text-gray-700 text-sm font-bold mb-2" for="name">Name</label>
          <input 
            type="text" 
            id="name" 
            bind:value={name} 
            class="shadow border-2 border-solid border-black  rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
            required

        >
        </div>
        
        <div class="mb-4">
          <label class="block text-gray-700 text-sm font-bold mb-2" for="description">Description</label>
          <textarea id="description" bind:value={description} class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required></textarea>
        </div>
        
        <div class="mb-4">
          <label class="block text-gray-700 text-sm font-bold mb-2" for="category">Category</label>
          <input type="text" id="category" bind:value={category} class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>
        
        <div class="mb-4">
          <label class="block text-gray-700 text-sm font-bold mb-2" for="price">Price</label>
          <input type="number" id="price" step="0.01" bind:value={price} class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>
        
        <div class="mb-4">
          <label class="block text-gray-700 text-sm font-bold mb-2" for="photoUrl">Photo URL</label>
          <input type="text" id="photoUrl" bind:value={photoUrl} class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mb-2">
          <input type="file" accept="image/*" on:change={handleFileUpload} class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>
        
        <div class="mb-4">
          <label class="block text-gray-700 text-sm font-bold mb-2" for="quantity">Quantity</label>
          <input type="number" id="quantity" bind:value={quantity} class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>
        
        <div class="flex items-center justify-center mx-auto">
          <button type="submit" class="w-1/2 rounded-md bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Submit</button>
        </div>
      </form>
      
</div>