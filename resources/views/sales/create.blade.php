<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Nueva Venta — {{ $business->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow-sm rounded-lg">

                <form method="POST" action="{{ route('businesses.sales.store', $business) }}" id="sale-form">
                    @csrf

                    {{-- Cliente y método de pago --}}
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Cliente</label>
                            <select name="customer_id" class="mt-1 w-full border-gray-300 rounded-md shadow-sm">
                                <option value="">Sin cliente</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Método de pago *</label>
                            <select name="payment_method" class="mt-1 w-full border-gray-300 rounded-md shadow-sm">
                                <option value="CASH">Efectivo</option>
                                <option value="CARD">Tarjeta</option>
                                <option value="TRANSFER">Transferencia</option>
                            </select>
                        </div>
                    </div>

                    {{-- Productos --}}
                    <div class="mb-6">
                        <div class="flex justify-between items-center mb-3">
                            <h3 class="font-semibold text-gray-700">Productos</h3>
                            <button type="button" onclick="addItem()"
                                    class="bg-green-600 text-white px-3 py-1 rounded text-sm hover:bg-green-700">
                                + Agregar producto
                            </button>
                        </div>

                        <div id="items-container">
                            {{-- Las filas de productos se agregan aquí con JS --}}
                        </div>

                        <div class="mt-2 text-sm text-gray-500" id="no-items-msg">
                            Agrega al menos un producto.
                        </div>
                    </div>

                    {{-- Descuento, impuesto y notas --}}
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Descuento ($)</label>
                            <input type="number" name="discount" step="0.01" value="0" min="0"
                                   oninput="updateTotal()"
                                   class="mt-1 w-full border-gray-300 rounded-md shadow-sm" id="discount">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Impuesto ($)</label>
                            <input type="number" name="tax" step="0.01" value="0" min="0"
                                   oninput="updateTotal()"
                                   class="mt-1 w-full border-gray-300 rounded-md shadow-sm" id="tax">
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700">Notas</label>
                        <textarea name="notes" rows="2"
                                  class="mt-1 w-full border-gray-300 rounded-md shadow-sm"></textarea>
                    </div>

                    {{-- Total --}}
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg text-right">
                        <span class="text-gray-600">Total: </span>
                        <span class="text-2xl font-bold text-gray-800">$<span id="total-display">0.00</span></span>
                    </div>

                    <div class="flex justify-end gap-3">
                        <a href="{{ route('businesses.sales.index', $business) }}"
                           class="px-4 py-2 border rounded text-gray-600 hover:bg-gray-50">Cancelar</a>
                        <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                            Registrar Venta
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Datos de productos para JavaScript --}}
    <script>
        const products = @json($products);
        let itemCount = 0;

        function addItem() {
            const container = document.getElementById('items-container');
            const noMsg = document.getElementById('no-items-msg');
            noMsg.style.display = 'none';

            const index = itemCount++;
            const options = products.map(p =>
                `<option value="${p.id}" data-price="${p.price}">${p.name} (Stock: ${p.stock})</option>`
            ).join('');

            const row = document.createElement('div');
            row.className = 'grid grid-cols-12 gap-2 mb-2 items-end';
            row.id = `item-${index}`;
            row.innerHTML = `
                <div class="col-span-5">
                    <label class="text-xs text-gray-500">Producto</label>
                    <select name="items[${index}][product_id]" onchange="setPrice(this, ${index})"
                            class="w-full border-gray-300 rounded-md shadow-sm text-sm">
                        ${options}
                    </select>
                </div>
                <div class="col-span-2">
                    <label class="text-xs text-gray-500">Cantidad</label>
                    <input type="number" name="items[${index}][quantity]" value="1" min="1"
                           oninput="updateTotal()"
                           class="w-full border-gray-300 rounded-md shadow-sm text-sm" id="qty-${index}">
                </div>
                <div class="col-span-3">
                    <label class="text-xs text-gray-500">Precio unitario</label>
                    <input type="number" name="items[${index}][unit_price]" step="0.01" min="0"
                           oninput="updateTotal()"
                           class="w-full border-gray-300 rounded-md shadow-sm text-sm" id="price-${index}"
                           value="${products[0]?.price ?? 0}">
                </div>
                <div class="col-span-2">
                    <button type="button" onclick="removeItem(${index})"
                            class="w-full px-2 py-2 bg-red-100 text-red-600 rounded hover:bg-red-200 text-sm">
                        Quitar
                    </button>
                </div>
            `;
            container.appendChild(row);
            updateTotal();
        }

        function setPrice(select, index) {
            const option = select.options[select.selectedIndex];
            document.getElementById(`price-${index}`).value = option.dataset.price;
            updateTotal();
        }

        function removeItem(index) {
            document.getElementById(`item-${index}`).remove();
            updateTotal();
            if (document.getElementById('items-container').children.length === 0) {
                document.getElementById('no-items-msg').style.display = 'block';
            }
        }

        function updateTotal() {
            let subtotal = 0;
            const container = document.getElementById('items-container');
            container.querySelectorAll('[id^="qty-"]').forEach(qtyInput => {
                const index = qtyInput.id.split('-')[1];
                const qty = parseFloat(qtyInput.value) || 0;
                const price = parseFloat(document.getElementById(`price-${index}`).value) || 0;
                subtotal += qty * price;
            });

            const discount = parseFloat(document.getElementById('discount').value) || 0;
            const tax = parseFloat(document.getElementById('tax').value) || 0;
            const total = subtotal - discount + tax;
            document.getElementById('total-display').textContent = total.toFixed(2);
        }
    </script>
</x-app-layout>