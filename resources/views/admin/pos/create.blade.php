@extends('layouts.main')

@section('title', 'Nova Venda - PDV')

@section('content')
<!-- Container de Notificações -->
<div id="notificationContainer" style="position: fixed; top: 20px; right: 20px; z-index: 9999; max-width: 400px;"></div>

<div style="background-color: #f3f4f6; padding: 20px; min-height: 100vh;">
    <div style="max-width: 1600px; margin: 0 auto;">
        <!-- Header -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
            <div>
                <h1 style="font-size: 28px; font-weight: 700; color: #1f2937; margin: 0;">Nova Venda</h1>
                <p style="color: #6b7280; font-size: 14px; margin-top: 4px;">Sistema de Ponto de Venda</p>
            </div>
            <a href="{{ route('admin.pos.index') }}" style="background-color: #6b7280; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: 600;">
                <i class="fas fa-arrow-left"></i> Voltar
            </a>
        </div>

        <form id="posForm" action="{{ route('admin.pos.store') }}" method="POST">
            @csrf
            <div style="display: grid; grid-template-columns: 1fr 400px; gap: 24px;">
                <!-- Área Principal -->
                <div style="display: flex; flex-direction: column; gap: 20px;">
                    <!-- Cliente -->
                    <div style="background: white; border-radius: 12px; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                        <h3 style="font-size: 18px; font-weight: 700; color: #1f2937; margin: 0 0 16px 0; display: flex; align-items: center; gap: 8px;">
                            <i class="fas fa-user" style="color: #667eea;"></i>
                            Cliente
                        </h3>

                        <div style="display: flex; gap: 12px; margin-bottom: 16px;">
                            <div style="flex: 1; position: relative;">
                                <input type="text" id="customerSearch" placeholder="Buscar cliente por CPF, nome, email ou telefone..." 
                                       style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px;">
                                <div id="customerResults" style="position: absolute; top: 100%; left: 0; right: 0; background: white; border: 1px solid #d1d5db; border-top: none; border-radius: 0 0 8px 8px; max-height: 200px; overflow-y: auto; z-index: 10; display: none;"></div>
                            </div>
                            <button type="button" id="newCustomerBtn" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 12px 20px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                                <i class="fas fa-plus"></i> Novo
                            </button>
                        </div>

                        <div id="selectedCustomer" style="display: none; background-color: #f0f9ff; border: 1px solid #0ea5e9; border-radius: 8px; padding: 16px;">
                            <div style="display: flex; justify-content: between; align-items: center;">
                                <div>
                                    <h4 style="margin: 0; color: #1e40af; font-weight: 600;" id="customerName"></h4>
                                    <p style="margin: 4px 0 0 0; color: #64748b; font-size: 14px;" id="customerInfo"></p>
                                </div>
                                <button type="button" id="removeCustomer" style="background: none; border: none; color: #ef4444; cursor: pointer; font-size: 16px;">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            <input type="hidden" name="customer_id" id="customerId">
                        </div>

                        <!-- Endereços do Cliente (aparecem após selecionar) -->
                        <div id="customerAddresses" style="display: none; margin-top: 16px;">
                            <label style="display: block; color: #374151; font-weight: 600; font-size: 14px; margin-bottom: 8px;">Endereço de Entrega:</label>
                            <div style="display: flex; gap: 8px; margin-bottom: 16px;">
                                <select name="address_id" id="addressSelect" style="flex: 1; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px;">
                                    <option value="">Selecione um endereço...</option>
                                </select>
                                <button type="button" id="newAddressBtn" onclick="openAddressModal()" style="background-color: #3b82f6; color: white; padding: 10px 16px; border: none; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; white-space: nowrap;">
                                    <i class="fas fa-plus"></i> Novo Endereço
                                </button>
                            </div>
                            
                            <!-- Campos de endereço manual (aparecem quando selecionado) -->
                            <div id="manualAddressFields" style="display: none; margin-top: 16px; padding: 16px; background-color: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px;">
                                <h4 style="margin: 0 0 12px 0; color: #374151; font-weight: 600;">Dados do Endereço:</h4>
                                <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 12px; margin-bottom: 12px;">
                                    <input type="text" name="manual_street" placeholder="Rua/Avenida" style="padding: 8px; border: 1px solid #d1d5db; border-radius: 4px; font-size: 14px;">
                                    <input type="text" name="manual_number" placeholder="Número" style="padding: 8px; border: 1px solid #d1d5db; border-radius: 4px; font-size: 14px;">
                                </div>
                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 12px;">
                                    <input type="text" name="manual_complement" placeholder="Complemento (opcional)" style="padding: 8px; border: 1px solid #d1d5db; border-radius: 4px; font-size: 14px;">
                                    <input type="text" name="manual_neighborhood" placeholder="Bairro" style="padding: 8px; border: 1px solid #d1d5db; border-radius: 4px; font-size: 14px;">
                                </div>
                                <div style="display: grid; grid-template-columns: 1fr 100px; gap: 12px;">
                                    <input type="text" name="manual_city" placeholder="Cidade" style="padding: 8px; border: 1px solid #d1d5db; border-radius: 4px; font-size: 14px;">
                                    <input type="text" name="manual_state" placeholder="UF" maxlength="2" style="padding: 8px; border: 1px solid #d1d5db; border-radius: 4px; font-size: 14px; text-transform: uppercase;">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Produtos -->
                    <div style="background: white; border-radius: 12px; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                        <h3 style="font-size: 18px; font-weight: 700; color: #1f2937; margin: 0 0 16px 0; display: flex; align-items: center; gap: 8px;">
                            <i class="fas fa-shopping-cart" style="color: #667eea;"></i>
                            Produtos
                        </h3>

                        <div style="position: relative; margin-bottom: 16px;">
                            <input type="text" id="productSearch" placeholder="Buscar produto por nome ou SKU..." 
                                   style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px;">
                            <div id="productResults" style="position: absolute; top: 100%; left: 0; right: 0; background: white; border: 1px solid #d1d5db; border-top: none; border-radius: 0 0 8px 8px; max-height: 300px; overflow-y: auto; z-index: 10; display: none;"></div>
                        </div>

                        <!-- Lista de produtos no carrinho -->
                        <div id="cartItems">
                            <div id="emptyCart" style="text-align: center; padding: 40px; color: #6b7280;">
                                <i class="fas fa-shopping-cart" style="font-size: 48px; margin-bottom: 16px; opacity: 0.5;"></i>
                                <p style="margin: 0; font-size: 16px;">Nenhum produto adicionado</p>
                                <p style="margin: 4px 0 0 0; font-size: 14px; opacity: 0.8;">Use a busca acima para adicionar produtos</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar - Resumo -->
                <div style="display: flex; flex-direction: column; gap: 20px;">
                    <!-- Resumo do Pedido -->
                    <div style="background: white; border-radius: 12px; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); position: sticky; top: 20px;">
                        <h3 style="font-size: 18px; font-weight: 700; color: #1f2937; margin: 0 0 20px 0; display: flex; align-items: center; gap: 8px;">
                            <i class="fas fa-receipt" style="color: #667eea;"></i>
                            Resumo
                        </h3>

                        <div style="border-bottom: 1px solid #e5e7eb; padding-bottom: 16px; margin-bottom: 16px;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                                <span style="color: #6b7280; font-size: 14px;">Subtotal:</span>
                                <span style="font-weight: 600; color: #1f2937;" id="subtotalDisplay">R$ 0,00</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                                <span style="color: #6b7280; font-size: 14px;">Frete:</span>
                                <span style="font-weight: 600; color: #1f2937;" id="shippingDisplay">R$ 0,00</span>
                            </div>
                            <div id="freeShippingNotice" style="display: none; background-color: #d1fae5; color: #065f46; padding: 8px 12px; border-radius: 6px; font-size: 12px; font-weight: 600; margin-top: 8px;">
                                <i class="fas fa-truck"></i> Frete grátis aplicado!
                            </div>
                        </div>

                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
                            <span style="font-size: 18px; font-weight: 700; color: #1f2937;">Total:</span>
                            <span style="font-size: 24px; font-weight: 700; color: #059669;" id="totalDisplay">R$ 0,00</span>
                        </div>

                        <!-- Método de Pagamento -->
                        <div style="margin-bottom: 20px;">
                            <label style="display: block; color: #374151; font-weight: 600; font-size: 14px; margin-bottom: 8px;">Método de Pagamento:</label>
                            <select name="payment_method" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px;" required>
                                <option value="">Selecione...</option>
                                <option value="cash">Dinheiro</option>
                                <option value="credit_card">Cartão de Crédito</option>
                                <option value="debit_card">Cartão de Débito</option>
                                <option value="pix">PIX</option>
                                <option value="bank_transfer">Transferência</option>
                            </select>
                        </div>

                        <!-- Observações -->
                        <div style="margin-bottom: 20px;">
                            <label style="display: block; color: #374151; font-weight: 600; font-size: 14px; margin-bottom: 8px;">Observações:</label>
                            <textarea name="notes" rows="3" placeholder="Observações sobre o pedido..." 
                                      style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; resize: vertical;"></textarea>
                        </div>

                        <!-- Botão Finalizar -->
                        <button type="submit" id="submitBtn" disabled style="width: 100%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 16px; border: none; border-radius: 8px; font-weight: 700; font-size: 16px; cursor: not-allowed; opacity: 0.6; transition: all 0.3s;">
                            <i class="fas fa-check-circle"></i> Finalizar Venda
                        </button>

                        <div id="submitError" style="display: none; margin-top: 12px; padding: 12px; background-color: #fee2e2; color: #991b1b; border-radius: 8px; font-size: 14px;">
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Novo Cliente -->
<div id="newCustomerModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background-color: rgba(0,0,0,0.5); z-index: 1000; display: flex; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 12px; padding: 24px; width: 500px; max-width: 90vw; max-height: 90vh; overflow-y: auto;">
        
        <!-- Etapa 1: Busca por CPF -->
        <div id="cpfSearchStep">
            <h3 style="margin: 0 0 20px 0; font-size: 20px; font-weight: 700; color: #1f2937;">Buscar Cliente</h3>
            
            <div style="margin-bottom: 16px;">
                <label style="display: block; color: #374151; font-weight: 600; font-size: 14px; margin-bottom: 8px;">CPF do Cliente</label>
                <input type="text" id="cpfSearch" placeholder="Digite o CPF (apenas números)..." maxlength="11" style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px;">
                <p style="margin: 8px 0 0 0; font-size: 12px; color: #6b7280;">Digite o CPF para verificar se o cliente já está cadastrado</p>
            </div>
            
            <div style="display: flex; gap: 12px; justify-content: flex-end;">
                <button type="button" id="cancelCpfSearch" style="background-color: #6b7280; color: white; padding: 10px 20px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                    Cancelar
                </button>
                <button type="button" id="searchCpfBtn" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 10px 20px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                    Buscar
                </button>
            </div>
        </div>
        
        <!-- Etapa 2: Cadastro Completo -->
        <div id="fullFormStep" style="display: none;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h3 style="margin: 0; font-size: 20px; font-weight: 700; color: #1f2937;">Novo Cliente</h3>
                <button type="button" id="backToCpfSearch" style="background: none; border: none; color: #667eea; font-size: 14px; cursor: pointer; font-weight: 600;">
                    <i class="fas fa-arrow-left"></i> Voltar
                </button>
            </div>
            
            <form id="newCustomerForm">
                <div style="margin-bottom: 16px;">
                    <label style="display: block; color: #374151; font-weight: 600; font-size: 14px; margin-bottom: 8px;">CPF *</label>
                    <input type="text" name="cpf" id="customerCpfInput" readonly style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; background-color: #f9fafb;">
                </div>
                
                <div style="margin-bottom: 16px;">
                    <label style="display: block; color: #374151; font-weight: 600; font-size: 14px; margin-bottom: 8px;">Nome *</label>
                    <input type="text" name="name" required style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px;">
                </div>
                
                <div style="margin-bottom: 16px;">
                    <label style="display: block; color: #374151; font-weight: 600; font-size: 14px; margin-bottom: 8px;">Email *</label>
                    <input type="email" name="email" required style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px;">
                </div>
                
                <div style="margin-bottom: 16px;">
                    <label style="display: block; color: #374151; font-weight: 600; font-size: 14px; margin-bottom: 8px;">Telefone</label>
                    <input type="text" name="phone" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px;" placeholder="Opcional">
                </div>
                
                <div style="display: flex; gap: 12px; justify-content: flex-end;">
                    <button type="button" id="cancelNewCustomer" style="background-color: #6b7280; color: white; padding: 10px 20px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                        Cancelar
                    </button>
                    <button type="submit" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 10px 20px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                        Criar Cliente
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Cadastro de Endereço -->
<div id="addressModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background-color: rgba(0,0,0,0.5); z-index: 1001; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 12px; padding: 24px; width: 600px; max-width: 90vw; max-height: 90vh; overflow-y: auto;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3 style="margin: 0; font-size: 20px; font-weight: 700; color: #1f2937;">Novo Endereço de Entrega</h3>
            <button type="button" id="closeAddressModal" style="background: none; border: none; color: #6b7280; font-size: 20px; cursor: pointer;">
                ✕
            </button>
        </div>
        
        <form id="addressForm">
            <!-- CEP - Primeiro Campo -->
            <div style="margin-bottom: 16px;">
                <label style="display: block; color: #374151; font-weight: 600; font-size: 14px; margin-bottom: 8px;">CEP *</label>
                <div style="display: flex; gap: 8px;">
                    <input type="text" name="cep" id="addressCep" required placeholder="00000-000" style="flex: 1; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px;">
                    <button type="button" id="searchCepBtn" onclick="searchCep()" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 10px 20px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; white-space: nowrap;">
                        <i class="fas fa-search"></i> Buscar
                    </button>
                </div>
                <p style="margin: 8px 0 0 0; font-size: 12px; color: #6b7280;">Digite o CEP e clique em "Buscar" para preencher os dados automaticamente</p>
                <div id="cepError" style="display: none; margin-top: 8px; padding: 8px 12px; background-color: #fee2e2; color: #991b1b; border-radius: 6px; font-size: 12px;"></div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px; display: none;" id="addressFieldsContainer">
                <div>
                    <label style="display: block; color: #374151; font-weight: 600; font-size: 14px; margin-bottom: 8px;">Rua *</label>
                    <input type="text" name="street" id="addressStreet" required readonly style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; background-color: #f9fafb;">
                </div>
                <div>
                    <label style="display: block; color: #374151; font-weight: 600; font-size: 14px; margin-bottom: 8px;">Número *</label>
                    <input type="text" name="number" id="addressNumber" required placeholder="Digite o número" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px;">
                </div>
            </div>

            <div style="margin-bottom: 16px; display: none;" id="addressComplementContainer">
                <label style="display: block; color: #374151; font-weight: 600; font-size: 14px; margin-bottom: 8px;">Complemento (opcional)</label>
                <input type="text" name="complement" id="addressComplement" placeholder="Apto, bloco, etc..." style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px;">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px; display: none;" id="addressCityFieldsContainer">
                <div>
                    <label style="display: block; color: #374151; font-weight: 600; font-size: 14px; margin-bottom: 8px;">Bairro *</label>
                    <input type="text" name="neighborhood" id="addressNeighborhood" required readonly style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; background-color: #f9fafb;">
                </div>
                <div>
                    <label style="display: block; color: #374151; font-weight: 600; font-size: 14px; margin-bottom: 8px;">Cidade *</label>
                    <input type="text" name="city" id="addressCity" required readonly style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; background-color: #f9fafb;">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 100px 1fr; gap: 16px; margin-bottom: 20px; display: none;" id="addressStateContainer">
                <div>
                    <label style="display: block; color: #374151; font-weight: 600; font-size: 14px; margin-bottom: 8px;">UF *</label>
                    <input type="text" name="state" id="addressState" maxlength="2" required readonly style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; text-transform: uppercase; background-color: #f9fafb;">
                </div>
                <div></div>
            </div>

            <div style="display: flex; gap: 12px; justify-content: flex-end; display: none;" id="addressSubmitContainer">
                <button type="button" id="cancelAddressForm" style="background-color: #6b7280; color: white; padding: 10px 20px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                    Cancelar
                </button>
                <button type="submit" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 10px 20px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                    <i class="fas fa-check"></i> Cadastrar Endereço
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Estado do carrinho
let cart = [];
let selectedCustomer = null;
let subtotal = 0;
let shipping = 0;
let total = 0;

// Elementos DOM
const customerSearch = document.getElementById('customerSearch');
const customerResults = document.getElementById('customerResults');
const productSearch = document.getElementById('productSearch');
const productResults = document.getElementById('productResults');
const cartItems = document.getElementById('cartItems');
const emptyCart = document.getElementById('emptyCart');
const newCustomerModal = document.getElementById('newCustomerModal');
const submitBtn = document.getElementById('submitBtn');

// Buscar clientes
let customerSearchTimeout;
customerSearch.addEventListener('input', function() {
    clearTimeout(customerSearchTimeout);
    const query = this.value.trim();
    
    if (query.length < 2) {
        customerResults.style.display = 'none';
        return;
    }
    
    customerSearchTimeout = setTimeout(() => {
        fetch(`{{ route('admin.pos.searchCustomers') }}?query=${encodeURIComponent(query)}`)
            .then(response => response.json())
            .then(customers => {
                displayCustomerResults(customers);
            })
            .catch(error => console.error('Error:', error));
    }, 300);
});

function displayCustomerResults(customers) {
    if (customers.length === 0) {
        customerResults.innerHTML = '<div style="padding: 12px; color: #6b7280; text-align: center;">Nenhum cliente encontrado</div>';
        customerResults.style.display = 'block';
        return;
    }
    
    customerResults.innerHTML = customers.map(customer => `
        <div onclick="selectCustomer(${JSON.stringify(customer).replace(/"/g, '&quot;')})" 
             style="padding: 12px; border-bottom: 1px solid #e5e7eb; cursor: pointer; transition: background-color 0.2s;"
             onmouseover="this.style.backgroundColor='#f3f4f6'"
             onmouseout="this.style.backgroundColor='white'">
            <div style="font-weight: 600; color: #1f2937;">${customer.name}</div>
            <div style="font-size: 12px; color: #6b7280;">${customer.cpf ? 'CPF: ' + customer.cpf : 'Sem CPF'}${customer.email ? ' • ' + customer.email : ''}${customer.phone ? ' • ' + customer.phone : ''}</div>
        </div>
    `).join('');
    
    customerResults.style.display = 'block';
}

function selectCustomer(customer) {
    selectedCustomer = customer;
    
    // Atualizar interface
    document.getElementById('customerId').value = customer.id;
    document.getElementById('customerName').textContent = customer.name;
    document.getElementById('customerInfo').textContent = `${customer.cpf ? 'CPF: ' + customer.cpf : 'Sem CPF'}${customer.email ? ' • ' + customer.email : ''}${customer.phone ? ' • ' + customer.phone : ''}`;
    document.getElementById('selectedCustomer').style.display = 'block';
    
    // Carregar endereços
    const addressSelect = document.getElementById('addressSelect');
    const customerAddresses = document.getElementById('customerAddresses');
    
    if (customer.addresses && customer.addresses.length > 0) {
        addressSelect.innerHTML = '<option value="">Selecione um endereço...</option>' +
            customer.addresses.map(address => `
                <option value="${address.id}">${address.street}, ${address.number}${address.complement ? ', ' + address.complement : ''} - ${address.neighborhood}, ${address.city}</option>
            `).join('');
        customerAddresses.style.display = 'block';
    } else {
        // Cliente sem endereços cadastrados - mostrar opção manual
        addressSelect.innerHTML = '<option value="manual">Inserir endereço manualmente</option>';
        customerAddresses.style.display = 'block';
        showNotification('Cliente sem endereço cadastrado. Endereço será inserido manualmente.', 'info');
    }
    
    // Limpar busca
    customerSearch.value = '';
    customerResults.style.display = 'none';
    
    updateTotals();
}

// Remover cliente selecionado
document.getElementById('removeCustomer').addEventListener('click', function() {
    selectedCustomer = null;
    document.getElementById('selectedCustomer').style.display = 'none';
    document.getElementById('customerAddresses').style.display = 'none';
    updateTotals();
});

// Buscar produtos
let productSearchTimeout;
productSearch.addEventListener('input', function() {
    clearTimeout(productSearchTimeout);
    const query = this.value.trim();
    
    if (query.length < 2) {
        productResults.style.display = 'none';
        return;
    }
    
    productSearchTimeout = setTimeout(() => {
        fetch(`{{ route('admin.pos.searchProducts') }}?query=${encodeURIComponent(query)}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then(response => {
                console.log('Response status:', response.status, response.statusText);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(products => {
                console.log('Products found:', products.length);
                displayProductResults(products);
            })
            .catch(error => {
                console.error('Error searching products:', error);
                showNotification('Erro ao buscar produtos. Verifique sua conexão e tente novamente.', 'error');
            });
    }, 300);
});

function displayProductResults(products) {
    if (products.length === 0) {
        productResults.innerHTML = '<div style="padding: 12px; color: #6b7280; text-align: center;">Nenhum produto encontrado</div>';
        productResults.style.display = 'block';
        return;
    }
    
    productResults.innerHTML = products.map(product => `
        <div onclick="addToCart(${JSON.stringify(product).replace(/"/g, '&quot;')})" 
             style="padding: 12px; border-bottom: 1px solid #e5e7eb; cursor: pointer; transition: background-color 0.2s; display: flex; justify-content: space-between; align-items: center;"
             onmouseover="this.style.backgroundColor='#f3f4f6'"
             onmouseout="this.style.backgroundColor='white'">
            <div>
                <div style="font-weight: 600; color: #1f2937;">${product.name}</div>
                <div style="font-size: 12px; color: #6b7280;">SKU: ${product.sku || 'N/A'} • Estoque: ${product.stock}</div>
            </div>
            <div style="text-align: right;">
                <div style="font-weight: 600; color: #059669;">R$ ${parseFloat(product.price).toLocaleString('pt-BR', {minimumFractionDigits: 2})}</div>
            </div>
        </div>
    `).join('');
    
    productResults.style.display = 'block';
}

function addToCart(product) {
    // Verificar se produto já está no carrinho
    const existingItem = cart.find(item => item.product.id === product.id);
    
    if (existingItem) {
        if (existingItem.quantity >= product.stock) {
            showNotification(`Quantidade máxima em estoque atingida (${product.stock} unidades)`, 'warning');
            return;
        }
        existingItem.quantity += 1;
    } else {
        cart.push({
            product: product,
            quantity: 1
        });
    }
    
    // Limpar busca
    productSearch.value = '';
    productResults.style.display = 'none';
    
    showNotification(`${product.name} adicionado ao carrinho`, 'success');
    updateCartDisplay();
    updateTotals();
}

function removeFromCart(productId) {
    cart = cart.filter(item => item.product.id !== productId);
    updateCartDisplay();
    updateTotals();
}

function updateQuantity(productId, change) {
    const item = cart.find(item => item.product.id === productId);
    if (!item) return;
    
    const newQuantity = item.quantity + change;
    
    if (newQuantity <= 0) {
        removeFromCart(productId);
        return;
    }
    
    if (newQuantity > item.product.stock) {
        showNotification(`Quantidade máxima em estoque: ${item.product.stock} unidades`, 'warning');
        return;
    }
    
    item.quantity = newQuantity;
    updateCartDisplay();
    updateTotals();
}

function updateCartDisplay() {
    if (cart.length === 0) {
        emptyCart.style.display = 'block';
        return;
    }
    
    emptyCart.style.display = 'none';
    
    cartItems.innerHTML = cart.map(item => `
        <div style="border: 1px solid #e5e7eb; border-radius: 8px; padding: 16px; margin-bottom: 12px; background-color: #fafafa;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
                <div style="flex: 1;">
                    <h4 style="margin: 0 0 4px 0; color: #1f2937; font-weight: 600; font-size: 16px;">${item.product.name}</h4>
                    <p style="margin: 0; color: #6b7280; font-size: 12px;">SKU: ${item.product.sku || 'N/A'} • Estoque: ${item.product.stock}</p>
                </div>
                <button onclick="removeFromCart(${item.product.id})" style="background: none; border: none; color: #ef4444; cursor: pointer; font-size: 16px; padding: 4px;">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div style="display: flex; align-items: center; gap: 8px;">
                    <button onclick="updateQuantity(${item.product.id}, -1)" type="button" style="width: 28px; height: 28px; background-color: #e5e7eb; border: none; border-radius: 4px; cursor: pointer; display: flex; align-items: center; justify-content: center;">-</button>
                    <span style="font-weight: 600; min-width: 20px; text-align: center;">${item.quantity}</span>
                    <button onclick="updateQuantity(${item.product.id}, 1)" type="button" style="width: 28px; height: 28px; background-color: #e5e7eb; border: none; border-radius: 4px; cursor: pointer; display: flex; align-items: center; justify-content: center;">+</button>
                </div>
                <div style="text-align: right;">
                    <div style="font-size: 12px; color: #6b7280;">R$ ${parseFloat(item.product.price).toLocaleString('pt-BR', {minimumFractionDigits: 2})} cada</div>
                    <div style="font-weight: 600; color: #059669;">R$ ${(item.quantity * parseFloat(item.product.price)).toLocaleString('pt-BR', {minimumFractionDigits: 2})}</div>
                </div>
            </div>
            <input type="hidden" name="items[${item.product.id}][product_id]" value="${item.product.id}">
            <input type="hidden" name="items[${item.product.id}][quantity]" value="${item.quantity}">
            <input type="hidden" name="items[${item.product.id}][price]" value="${item.product.price}">
        </div>
    `).join('');
}

function updateTotals() {
    // Calcular subtotal
    subtotal = cart.reduce((sum, item) => sum + (item.quantity * parseFloat(item.product.price)), 0);
    
    // Calcular frete se houver cliente e endereço
    if (selectedCustomer && document.getElementById('addressSelect').value) {
        calculateShipping();
    } else {
        shipping = 0;
        updateTotalDisplay();
    }
    
    // Verificar se pode finalizar
    const canSubmit = cart.length > 0 && selectedCustomer && document.querySelector('[name="payment_method"]').value;
    submitBtn.disabled = !canSubmit;
    submitBtn.style.opacity = canSubmit ? '1' : '0.6';
    submitBtn.style.cursor = canSubmit ? 'pointer' : 'not-allowed';
}

function calculateShipping() {
    const addressId = document.getElementById('addressSelect').value;
    if (!addressId || subtotal === 0) {
        shipping = 0;
        updateTotalDisplay();
        return;
    }
    
    let requestData = { total: subtotal };
    
    if (addressId === 'manual') {
        // Usar endereço manual
        const street = document.querySelector('[name="manual_street"]').value;
        const number = document.querySelector('[name="manual_number"]').value;
        const neighborhood = document.querySelector('[name="manual_neighborhood"]').value;
        const city = document.querySelector('[name="manual_city"]').value;
        const state = document.querySelector('[name="manual_state"]').value;
        
        if (!street || !number || !neighborhood || !city || !state) {
            // Campos obrigatórios não preenchidos
            shipping = 15.00; // Frete padrão
            updateTotalDisplay();
            return;
        }
        
        requestData.manual_address = {
            street, number, 
            complement: document.querySelector('[name="manual_complement"]').value,
            neighborhood, city, state
        };
    } else {
        requestData.address_id = addressId;
    }
    
    fetch(`{{ route('admin.pos.calculateShipping') }}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('[name="_token"]').value
        },
        body: JSON.stringify(requestData)
    })
    .then(response => response.json())
    .then(data => {
        shipping = data.shipping || 15.00;
        updateTotalDisplay();
        
        // Mostrar aviso de frete grátis
        const freeShippingNotice = document.getElementById('freeShippingNotice');
        if (data.free_shipping) {
            freeShippingNotice.style.display = 'block';
        } else {
            freeShippingNotice.style.display = 'none';
        }
    })
    .catch(error => {
        console.error('Error calculating shipping:', error);
        shipping = 15.00; // Frete padrão em caso de erro
        updateTotalDisplay();
    });
}

function updateTotalDisplay() {
    total = subtotal + shipping;
    
    document.getElementById('subtotalDisplay').textContent = `R$ ${subtotal.toLocaleString('pt-BR', {minimumFractionDigits: 2})}`;
    document.getElementById('shippingDisplay').textContent = `R$ ${shipping.toLocaleString('pt-BR', {minimumFractionDigits: 2})}`;
    document.getElementById('totalDisplay').textContent = `R$ ${total.toLocaleString('pt-BR', {minimumFractionDigits: 2})}`;
    
    // Atualizar campos hidden
    if (!document.querySelector('[name="subtotal"]')) {
        const form = document.getElementById('posForm');
        form.insertAdjacentHTML('beforeend', `
            <input type="hidden" name="subtotal" value="${subtotal}">
            <input type="hidden" name="shipping" value="${shipping}">
            <input type="hidden" name="total" value="${total}">
        `);
    } else {
        document.querySelector('[name="subtotal"]').value = subtotal;
        document.querySelector('[name="shipping"]').value = shipping;
        document.querySelector('[name="total"]').value = total;
    }
}

// Eventos para recalcular frete
document.getElementById('addressSelect').addEventListener('change', function() {
    const manualAddressFields = document.getElementById('manualAddressFields');
    
    if (this.value === 'manual') {
        manualAddressFields.style.display = 'block';
        showNotification('Preencha os dados do endereço para calcular o frete', 'info');
    } else {
        manualAddressFields.style.display = 'none';
        calculateShipping();
    }
});

// Adicionar eventos nos campos de endereço manual para recalcular frete
const manualFields = ['manual_street', 'manual_number', 'manual_neighborhood', 'manual_city', 'manual_state'];
manualFields.forEach(fieldName => {
    const field = document.querySelector(`[name="${fieldName}"]`);
    if (field) {
        field.addEventListener('blur', function() {
            // Verificar se todos os campos obrigatórios estão preenchidos
            const street = document.querySelector('[name="manual_street"]').value;
            const number = document.querySelector('[name="manual_number"]').value;
            const neighborhood = document.querySelector('[name="manual_neighborhood"]').value;
            const city = document.querySelector('[name="manual_city"]').value;
            const state = document.querySelector('[name="manual_state"]').value;
            
            if (street && number && neighborhood && city && state) {
                calculateShipping();
            }
        });
    }
});

document.querySelector('[name="payment_method"]').addEventListener('change', updateTotals);

// Modal novo cliente
document.getElementById('newCustomerBtn').addEventListener('click', function() {
    newCustomerModal.style.display = 'flex';
    document.getElementById('cpfSearchStep').style.display = 'block';
    document.getElementById('fullFormStep').style.display = 'none';
    document.getElementById('cpfSearch').focus();
});

// Máscara para CPF (apenas números)
document.getElementById('cpfSearch').addEventListener('input', function() {
    this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11);
});

// Busca por CPF
document.getElementById('searchCpfBtn').addEventListener('click', function() {
    const cpf = document.getElementById('cpfSearch').value.trim();
    
    if (!cpf || cpf.length !== 11) {
        showNotification('Digite um CPF válido com 11 dígitos', 'error');
        return;
    }
    
    // Validar CPF antes da busca
    if (!isValidCPF(cpf)) {
        showNotification('CPF inválido. Por favor, verifique os números digitados.', 'error');
        return;
    }
    
    // Buscar cliente por CPF
    fetch(`{{ route('admin.pos.searchByCpf') }}?cpf=${encodeURIComponent(cpf)}`)
        .then(response => response.json())
        .then(data => {
            if (data.found) {
                // Cliente encontrado - selecionar automaticamente
                selectCustomer(data.customer);
                newCustomerModal.style.display = 'none';
                resetModal();
                showNotification('Cliente encontrado e selecionado!', 'success');
            } else {
                // Cliente não encontrado - mostrar formulário completo
                document.getElementById('cpfSearchStep').style.display = 'none';
                document.getElementById('fullFormStep').style.display = 'block';
                document.getElementById('customerCpfInput').value = cpf;
                document.querySelector('[name="name"]').focus();
                showNotification('Cliente não encontrado. Preencha os dados para criar um novo.', 'info');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Erro ao buscar cliente. Tente novamente.', 'error');
        });
});

// Voltar para busca de CPF
document.getElementById('backToCpfSearch').addEventListener('click', function() {
    document.getElementById('fullFormStep').style.display = 'none';
    document.getElementById('cpfSearchStep').style.display = 'block';
    document.getElementById('cpfSearch').focus();
});

// Cancelar busca por CPF
document.getElementById('cancelCpfSearch').addEventListener('click', function() {
    newCustomerModal.style.display = 'none';
    resetModal();
});

document.getElementById('cancelNewCustomer').addEventListener('click', function() {
    newCustomerModal.style.display = 'none';
    resetModal();
});

function resetModal() {
    document.getElementById('cpfSearch').value = '';
    document.getElementById('newCustomerForm').reset();
    document.getElementById('cpfSearchStep').style.display = 'block';
    document.getElementById('fullFormStep').style.display = 'none';
}

document.getElementById('newCustomerForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch(`{{ route('admin.pos.createCustomer') }}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('[name="_token"]').value
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            selectCustomer(data.customer);
            newCustomerModal.style.display = 'none';
            this.reset();
            showNotification('Cliente criado e selecionado com sucesso!', 'success');
        } else {
            showNotification('Erro ao criar cliente: ' + (data.message || 'Erro desconhecido'), 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Erro ao criar cliente. Tente novamente.', 'error');
    });
});

// Fechar dropdowns ao clicar fora
document.addEventListener('click', function(e) {
    if (!customerSearch.contains(e.target) && !customerResults.contains(e.target)) {
        customerResults.style.display = 'none';
    }
    if (!productSearch.contains(e.target) && !productResults.contains(e.target)) {
        productResults.style.display = 'none';
    }
});

// Submit form
document.getElementById('posForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const submitError = document.getElementById('submitError');
    
    if (cart.length === 0) {
        submitError.innerHTML = 'Adicione pelo menos um produto ao carrinho';
        submitError.style.display = 'block';
        showNotification('Adicione pelo menos um produto ao carrinho', 'warning');
        return;
    }
    
    if (!selectedCustomer) {
        submitError.innerHTML = 'Selecione um cliente';
        submitError.style.display = 'block';
        showNotification('Selecione um cliente para continuar', 'warning');
        return;
    }
    
    if (!document.querySelector('[name="payment_method"]').value) {
        submitError.innerHTML = 'Selecione um método de pagamento';
        submitError.style.display = 'block';
        showNotification('Selecione um método de pagamento', 'warning');
        return;
    }
    
    submitError.style.display = 'none';
    
    // Desabilitar botão durante envio
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processando...';
    
    showNotification('Processando venda...', 'info');
    
    // Enviar formulário
    this.submit();
});

// Inicialização
updateCartDisplay();
updateTotals();

// Função para exibir notificações
function showNotification(message, type = 'info') {
    const container = document.getElementById('notificationContainer');
    
    const notification = document.createElement('div');
    notification.style.cssText = `
        margin-bottom: 10px;
        padding: 16px 20px;
        border-radius: 8px;
        color: white;
        font-weight: 600;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        transform: translateX(100%);
        transition: all 0.3s ease;
        cursor: pointer;
        position: relative;
        overflow: hidden;
    `;
    
    // Define cores baseadas no tipo
    const colors = {
        success: 'linear-gradient(135deg, #10b981 0%, #059669 100%)',
        error: 'linear-gradient(135deg, #ef4444 0%, #dc2626 100%)',
        warning: 'linear-gradient(135deg, #f59e0b 0%, #d97706 100%)',
        info: 'linear-gradient(135deg, #3b82f6 0%, #2563eb 100%)'
    };
    
    notification.style.background = colors[type] || colors.info;
    
    // Ícones para cada tipo
    const icons = {
        success: '✓',
        error: '✗',
        warning: '⚠',
        info: 'ℹ'
    };
    
    notification.innerHTML = `
        <div style="display: flex; align-items: center; gap: 8px;">
            <span style="font-size: 16px;">${icons[type] || icons.info}</span>
            <span>${message}</span>
        </div>
    `;
    
    container.appendChild(notification);
    
    // Animação de entrada
    setTimeout(() => {
        notification.style.transform = 'translateX(0)';
    }, 50);
    
    // Remove automaticamente após 5 segundos
    setTimeout(() => {
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }, 5000);
    
    // Remove ao clicar
    notification.addEventListener('click', () => {
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    });
}

// Funções do modal de endereço
function searchCep() {
    const cepInput = document.getElementById('addressCep');
    const cepError = document.getElementById('cepError');
    
    // Remover máscara e validar
    let cep = cepInput.value.replace(/[^0-9]/g, '');
    
    if (!cep || cep.length !== 8) {
        cepError.textContent = 'CEP deve conter 8 dígitos';
        cepError.style.display = 'block';
        return;
    }
    
    // Limpar erro
    cepError.style.display = 'none';
    
    // Mostrar loading
    const searchBtn = document.getElementById('searchCepBtn');
    const originalText = searchBtn.innerHTML;
    searchBtn.disabled = true;
    searchBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Buscando...';
    
    // Consultar ViaCEP
    fetch(`https://viacep.com.br/ws/${cep}/json/`)
        .then(response => response.json())
        .then(data => {
            if (data.erro) {
                cepError.textContent = 'CEP não encontrado';
                cepError.style.display = 'block';
                searchBtn.disabled = false;
                searchBtn.innerHTML = originalText;
                
                // Esconder campos
                document.getElementById('addressFieldsContainer').style.display = 'none';
                document.getElementById('addressComplementContainer').style.display = 'none';
                document.getElementById('addressCityFieldsContainer').style.display = 'none';
                document.getElementById('addressStateContainer').style.display = 'none';
                document.getElementById('addressSubmitContainer').style.display = 'none';
                return;
            }
            
            // Preencher campos automaticamente
            const streetField = document.getElementById('addressStreet');
            const neighborhoodField = document.getElementById('addressNeighborhood');
            const cityField = document.getElementById('addressCity');
            const stateField = document.getElementById('addressState');
            
            streetField.value = data.logradouro || '';
            neighborhoodField.value = data.bairro || '';
            cityField.value = data.localidade || '';
            stateField.value = data.uf || '';
            
            // Liberar campos que vêm vazios da ViaCEP
            if (!data.logradouro || data.logradouro.trim() === '') {
                streetField.removeAttribute('readonly');
                streetField.style.backgroundColor = '#ffffff';
                streetField.placeholder = 'Digite a rua manualmente';
            } else {
                streetField.setAttribute('readonly', true);
                streetField.style.backgroundColor = '#f9fafb';
            }
            
            if (!data.bairro || data.bairro.trim() === '') {
                neighborhoodField.removeAttribute('readonly');
                neighborhoodField.style.backgroundColor = '#ffffff';
                neighborhoodField.placeholder = 'Digite o bairro manualmente';
            } else {
                neighborhoodField.setAttribute('readonly', true);
                neighborhoodField.style.backgroundColor = '#f9fafb';
            }
            
            // Cidade e UF sempre vêm preenchidos, mantêm readonly
            cityField.setAttribute('readonly', true);
            cityField.style.backgroundColor = '#f9fafb';
            stateField.setAttribute('readonly', true);
            stateField.style.backgroundColor = '#f9fafb';
            
            // Mostrar campos
            document.getElementById('addressFieldsContainer').style.display = 'grid';
            document.getElementById('addressComplementContainer').style.display = 'block';
            document.getElementById('addressCityFieldsContainer').style.display = 'grid';
            document.getElementById('addressStateContainer').style.display = 'grid';
            document.getElementById('addressSubmitContainer').style.display = 'flex';
            
            // Focar no campo de número
            document.getElementById('addressNumber').focus();
            
            showNotification('Dados do CEP preenchidos com sucesso!', 'success');
            searchBtn.disabled = false;
            searchBtn.innerHTML = originalText;
        })
        .catch(error => {
            console.error('Erro ao buscar CEP:', error);
            cepError.textContent = 'Erro ao buscar CEP. Tente novamente.';
            cepError.style.display = 'block';
            searchBtn.disabled = false;
            searchBtn.innerHTML = originalText;
            
            // Esconder campos
            document.getElementById('addressFieldsContainer').style.display = 'none';
            document.getElementById('addressComplementContainer').style.display = 'none';
            document.getElementById('addressCityFieldsContainer').style.display = 'none';
            document.getElementById('addressStateContainer').style.display = 'none';
            document.getElementById('addressSubmitContainer').style.display = 'none';
        });
}

// Máscara para CEP
document.addEventListener('DOMContentLoaded', function() {
    const cepInput = document.getElementById('addressCep');
    if (cepInput) {
        cepInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/[^0-9]/g, '');
            if (value.length > 5) {
                value = value.substring(0, 5) + '-' + value.substring(5, 8);
            }
            e.target.value = value;
        });
    }
});

function openAddressModal() {
    if (!selectedCustomer) {
        showNotification('Selecione um cliente primeiro', 'warning');
        return;
    }
    
    document.getElementById('addressModal').style.display = 'flex';
    document.getElementById('addressForm').reset();
    document.querySelector('[name="street"]').focus();
}

document.getElementById('closeAddressModal').addEventListener('click', function() {
    document.getElementById('addressModal').style.display = 'none';
});

document.getElementById('cancelAddressForm').addEventListener('click', function() {
    document.getElementById('addressModal').style.display = 'none';
});

document.getElementById('addressForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    formData.append('customer_id', selectedCustomer.id);
    
    // Disabilitar botão
    const submitBtn = this.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Cadastrando...';
    
    fetch(`{{ route('admin.pos.createAddress') }}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('[name="_token"]').value
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Atualizar cliente com novo endereço
            selectCustomer(data.customer);
            
            // Fechar modal
            document.getElementById('addressModal').style.display = 'none';
            this.reset();
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-check"></i> Cadastrar Endereço';
            
            showNotification('Endereço cadastrado com sucesso!', 'success');
        } else {
            showNotification('Erro ao cadastrar endereço: ' + (data.message || 'Erro desconhecido'), 'error');
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-check"></i> Cadastrar Endereço';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Erro ao cadastrar endereço. Tente novamente.', 'error');
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fas fa-check"></i> Cadastrar Endereço';
    });
});

// Função para validar CPF
function isValidCPF(cpf) {
    // Remove caracteres não numéricos
    cpf = cpf.replace(/[^0-9]/g, '');

    // Verifica se tem 11 dígitos
    if (cpf.length !== 11) {
        return false;
    }

    // Verifica se todos os dígitos são iguais
    if (/^(\d)\1{10}$/.test(cpf)) {
        return false;
    }

    // Validação do primeiro dígito verificador
    let sum = 0;
    for (let i = 0; i < 9; i++) {
        sum += parseInt(cpf[i]) * (10 - i);
    }
    let digit1 = 11 - (sum % 11);
    digit1 = digit1 > 9 ? 0 : digit1;

    if (parseInt(cpf[9]) !== digit1) {
        return false;
    }

    // Validação do segundo dígito verificador
    sum = 0;
    for (let i = 0; i < 10; i++) {
        sum += parseInt(cpf[i]) * (11 - i);
    }
    let digit2 = 11 - (sum % 11);
    digit2 = digit2 > 9 ? 0 : digit2;

    if (parseInt(cpf[10]) !== digit2) {
        return false;
    }

    return true;
}
</script>

@endsection