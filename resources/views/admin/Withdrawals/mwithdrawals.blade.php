<?php
if (Auth('admin')->User()->dashboard_style == 'light') {
    $text = 'dark';
} else {
    $text = 'light';
}
?>
@extends('layouts.app')
@section('content')
    @include('admin.topmenu')
    @include('admin.sidebar')
    <div class="main-panel">
        <div class="content ">
            <div class="page-inner">
                <div class="mt-2 mb-4">
                    <h1 class="title1 ">Manage clients withdrawals</h1>
                </div>
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong><i class="fa fa-check-circle"></i> Success!</strong> {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong><i class="fa fa-times-circle"></i> Error!</strong> {{ session('error') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if(session('message'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong><i class="fa fa-exclamation-triangle"></i> Alert!</strong> {{ session('message') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if(session('info'))
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <strong><i class="fa fa-info-circle"></i> Info!</strong> {{ session('info') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if(session('warning'))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <strong><i class="fa fa-exclamation-circle"></i> Warning!</strong> {{ session('warning') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong><i class="fa fa-exclamation-triangle"></i> Validation Errors!</strong>
                        <ul class="mb-0 mt-2">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <div class="mb-5 row">
                    <div class="col card p-3 shadow ">
                        <div class="bs-example widget-shadow table-responsive" data-example-id="hoverable-table">
                            <span style="margin:3px;">
                                <table id="ShipTable" class="table table-hover ">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Account Holder</th>
                                            <th>Amount </th>
                                            <th>Description</th>
                                            <th>Type</th>
                                            <th>Payment Method</th>
                                            <th>Party Bank</th>
                                            <th>Beneficiary</th>
                                            <th>Address</th>
                                            <th>Country</th>
                                            <th>Status</th>
                                            <th>Date created</th>
                                            <th>Option</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($withdrawals as $deposit)
                                            <tr>
                                                <th scope="row">{{ $deposit->id }}</th>
                                                <td>{{ $deposit->duser->name }}</td>
                                                <td>{{ $deposit->duser->currency }}{{ number_format($deposit->amount) }}
                                                </td>
                                                <td>{{ $deposit->Description }}</td>
                                                <td>
                                                    @php $depStatus = strtolower($deposit->status ?? ''); @endphp
                                                    @if(in_array($depStatus, ['failed', 'error'], true))
                                                        <span class="badge badge-danger">{{ $deposit->status }}</span>
                                                    @elseif($depStatus === 'pending')
                                                        <span class="badge badge-warning">{{ $deposit->status }}</span>
                                                    @elseif($deposit->type === 'Credit')
                                                        <span class="badge badge-success">Credit</span>
                                                    @else
                                                        <span class="badge badge-danger">Debit</span>
                                                    @endif
                                                </td>
                                                <td>{{ $deposit->payment_mode }}</td>
                                                <td>
                                                    @if(!empty($deposit->sender_bank))
                                                        <div class="d-flex align-items-center">
                                                            <i class="fa fa-university mr-1" style="color: #2d8659;"></i>
                                                            <strong style="color: #2d8659;">{{ $deposit->sender_bank }}</strong>
                                                        </div>
                                                        @if(!empty($deposit->reference_number))
                                                            <small class="text-muted d-block">Ref: {{ $deposit->reference_number }}</small>
                                                        @endif
                                                        @if(!empty($deposit->sender_name))
                                                            <small class="text-muted d-block">{{ $deposit->sender_name }}</small>
                                                        @endif
                                                    @else
                                                        <span class="text-muted">—</span>
                                                    @endif
                                                </td>
                                                <td>{{ $deposit->accountname }}</td>
                                                
                                                 <td>{{ $deposit->bankaddress }}</td>
                                                 
                                                 <td>{{ $deposit->country }}</td>
                                                <td>
                                                    @if ($deposit->status == 'Processed')
                                                        <span class="badge badge-success">{{ $deposit->status }}</span>
                                                    @elseif($deposit->status == 'On-hold' || $deposit->status == 'Pending' || $deposit->status == 'Under Review')
                                                        <span class="badge badge-warning">{{ $deposit->status }}</span>
                                                    @elseif($deposit->status == 'Reversed' || $deposit->status == 'Refunded')
                                                        <span class="badge badge-info">{{ $deposit->status }}</span>
                                                    @elseif($deposit->status == 'Scheduled')
                                                        <span class="badge badge-primary">{{ $deposit->status }}</span>
                                                    @elseif($deposit->status == 'Cancelled')
                                                        <span class="badge badge-secondary">{{ $deposit->status }}</span>
                                                    @elseif($deposit->status == 'Expired' || $deposit->status == 'Frozen')
                                                        <span class="badge badge-dark">{{ $deposit->status }}</span>
                                                    @else
                                                        <span class="badge badge-danger">{{ $deposit->status }}</span>
                                                    @endif
                                                </td>
                                                <td>{{ \Carbon\Carbon::parse($deposit->created_at)->toDayDateTimeString() }}
                                                </td>
                                                <td>
                                                    <a href="{{ route('processwithdraw', $deposit->id) }}"
                                                        class="m-1 btn btn-info btn-sm"><i class="fa fa-eye"></i> View</a>
                                                    <button type="button" class="m-1 btn btn-primary btn-sm" 
                                                            onclick="openEditModal({{ json_encode($deposit) }})">
                                                        <i class="fa fa-edit"></i> Edit
                                                    </button>
                                                    <button type="button" class="m-1 btn btn-danger btn-sm" 
                                                            onclick="confirmDelete({{ $deposit->id }}, '{{ $deposit->txn_id }}')">
                                                        <i class="fa fa-trash"></i> Delete
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Withdrawal Modal -->
        <div class="modal fade" id="editWithdrawalModal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <form id="editWithdrawalForm" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Withdrawal/Transaction</h5>
                            <button type="button" class="close" data-dismiss="modal">
                                <span>&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" id="edit_withdrawal_id" name="withdrawal_id">
                            
                            <!-- Transaction Info -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="font-weight-bold">Transaction ID</label>
                                    <input type="text" class="form-control" id="edit_txn_id" name="txn_id" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="font-weight-bold">Account Holder</label>
                                    <input type="text" class="form-control" id="edit_user_name" readonly>
                                </div>
                            </div>

                            <!-- Amount, Type, Status -->
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="font-weight-bold">Amount <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" class="form-control" id="edit_amount" name="amount" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="font-weight-bold">Type <span class="text-danger">*</span></label>
                                    <select class="form-control" id="edit_type" name="type" required onchange="toggleSenderFields()">
                                        <option value="Credit">Credit</option>
                                        <option value="Debit">Debit</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="font-weight-bold">Status <span class="text-danger">*</span></label>
                                    <select class="form-control" id="edit_status" name="status" required>
                                        <option value="Pending">Pending</option>
                                        <option value="Processed">Processed</option>
                                        <option value="On-hold">On-hold</option>
                                        <option value="Rejected">Rejected</option>
                                        <option value="Failed">Failed</option>
                                        <option value="Cancelled">Cancelled</option>
                                        <option value="Reversed">Reversed</option>
                                        <option value="Refunded">Refunded</option>
                                        <option value="Scheduled">Scheduled</option>
                                        <option value="Expired">Expired</option>
                                        <option value="Under Review">Under Review</option>
                                        <option value="Frozen">Frozen</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Payment Mode -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="font-weight-bold">Payment Method <span class="text-danger">*</span></label>
                                    <select class="form-control" id="edit_payment_mode" name="payment_mode" required onchange="togglePaymentFields()">
                                        <option value="Wire Transfer">Wire Transfer</option>
                                        <option value="Internal Transfer">Internal Transfer</option>
                                        <option value="Domestic Transfer">Domestic Transfer</option>
                                        <option value="Local transfer">Local Transfer</option>
                                        <option value="Western Union">Western Union</option>
                                        <option value="Bitcoin">Bitcoin</option>
                                        <option value="Ethereum">Ethereum</option>
                                        <option value="USDT">USDT</option>
                                        <option value="Cryptocurrency">Cryptocurrency</option>
                                        <option value="PayPal">PayPal</option>
                                        <option value="Skrill">Skrill</option>
                                        <option value="Payoneer">Payoneer</option>
                                        <option value="TransferWise">TransferWise</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="font-weight-bold">Transaction Date</label>
                                    <input type="datetime-local" class="form-control" id="edit_date" name="date">
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="row mb-3">
                                <div class="col-12">
                                    <label class="font-weight-bold">Description</label>
                                    <textarea class="form-control" id="edit_description" name="Description" rows="2"></textarea>
                                </div>
                            </div>

                            <!-- Sender/Party Information (For Credits and tracked Debits) -->
                            <div id="senderFieldsSection" class="border-top pt-3 mb-3" style="display: none;">
                                <h6 class="font-weight-bold mb-3" id="senderHeaderText">Sender Information</h6>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label id="senderNameLabel">Sender Name</label>
                                        <input type="text" class="form-control" id="edit_sender_name" name="sender_name">
                                    </div>
                                    <div class="col-md-6">
                                        <label id="senderBankLabel">Sender Bank</label>
                                        <input type="text" class="form-control" id="edit_sender_bank" name="sender_bank">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label id="senderAccountLabel">Sender Account Number</label>
                                        <input type="text" class="form-control" id="edit_sender_account_number" name="sender_account_number">
                                    </div>
                                    <div class="col-md-6">
                                        <label>Reference Number</label>
                                        <input type="text" class="form-control" id="edit_reference_number" name="reference_number">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <label id="senderAddressLabel">Sender Bank Address</label>
                                        <textarea class="form-control" id="edit_sender_bank_address" name="sender_bank_address" rows="2"></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Beneficiary Information -->
                            <div class="border-top pt-3 mb-3">
                                <h6 class="font-weight-bold mb-3">Beneficiary Information</h6>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="font-weight-bold">Account Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="edit_accountname" name="accountname" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="font-weight-bold" id="accountNumberLabel">Account Number <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="edit_accountnumber" name="accountnumber" required>
                                    </div>
                                </div>

                                <!-- Bank Transfer Fields -->
                                <div id="bankTransferFields">
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label>Bank Name</label>
                                            <input type="text" class="form-control" id="edit_bankname" name="bankname">
                                        </div>
                                        <div class="col-md-6">
                                            <label>Account Type</label>
                                            <select class="form-control" id="edit_accounttype" name="Accounttype">
                                                <option value="">Select Account Type</option>
                                                <option value="Checking">Checking</option>
                                                <option value="Savings">Savings</option>
                                                <option value="Business">Business</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <label>SWIFT Code</label>
                                            <input type="text" class="form-control" id="edit_swiftcode" name="swiftcode">
                                        </div>
                                        <div class="col-md-4">
                                            <label>IBAN</label>
                                            <input type="text" class="form-control" id="edit_iban" name="iban">
                                        </div>
                                        <div class="col-md-4">
                                            <label>Routing Number</label>
                                            <input type="text" class="form-control" id="edit_routingnumber" name="routingnumber">
                                        </div>
                                    </div>
                                </div>

                                <!-- Location Fields -->
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label>Country</label>
                                        <input type="text" class="form-control" id="edit_country" name="country">
                                    </div>
                                    <div class="col-md-6">
                                        <label>City</label>
                                        <input type="text" class="form-control" id="edit_city" name="city">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label>Zip Code</label>
                                        <input type="text" class="form-control" id="edit_zipcode" name="zipcode">
                                    </div>
                                    <div class="col-md-6">
                                        <label>Bank Address</label>
                                        <input type="text" class="form-control" id="edit_bankaddress" name="bankaddress">
                                    </div>
                                </div>
                            </div>

                            <!-- Additional Details -->
                            <div class="border-top pt-3 mb-3">
                                <h6 class="font-weight-bold mb-3">Additional Information</h6>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label>Balance After Transaction</label>
                                        <input type="number" step="0.01" class="form-control" id="edit_bal" name="bal">
                                    </div>
                                    <div class="col-md-6">
                                        <label>Amount to Deduct</label>
                                        <input type="number" step="0.01" class="form-control" id="edit_to_deduct" name="to_deduct">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <label>Payment Details</label>
                                        <textarea class="form-control" id="edit_paydetails" name="paydetails" rows="2"></textarea>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <label>Admin Note</label>
                                        <textarea class="form-control" id="edit_admin_note" name="admin_note" rows="2" placeholder="Internal notes (not visible to user)"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save"></i> Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <form id="deleteWithdrawalForm" method="POST" style="display: none;">
            @csrf
            @method('DELETE')
        </form>

        <script>
            function confirmDelete(id, txnId) {
                if (confirm('Are you sure you want to delete transaction ' + txnId + '?\n\nThis action cannot be undone!')) {
                    const form = document.getElementById('deleteWithdrawalForm');
                    form.action = "{{ url('admin/dashboard/withdrawals') }}/" + id;
                    form.submit();
                }
            }

            function openEditModal(withdrawal) {
                // Set form action URL
                document.getElementById('editWithdrawalForm').action = "{{ url('admin/dashboard/withdrawals') }}/" + withdrawal.id;
                
                // Fill basic fields
                document.getElementById('edit_withdrawal_id').value = withdrawal.id;
                document.getElementById('edit_txn_id').value = withdrawal.txn_id || '';
                document.getElementById('edit_user_name').value = withdrawal.duser ? withdrawal.duser.name : '';
                document.getElementById('edit_amount').value = withdrawal.amount || '';
                document.getElementById('edit_type').value = withdrawal.type || 'Debit';
                document.getElementById('edit_status').value = withdrawal.status || 'Pending';
                document.getElementById('edit_payment_mode').value = withdrawal.payment_mode || '';
                document.getElementById('edit_description').value = withdrawal.Description || '';
                
                // Format date for datetime-local input
                if (withdrawal.created_at) {
                    const date = new Date(withdrawal.created_at);
                    const formattedDate = date.toISOString().slice(0, 16);
                    document.getElementById('edit_date').value = formattedDate;
                }
                
                // Sender/Party fields
                document.getElementById('edit_sender_name').value = withdrawal.sender_name || '';
                document.getElementById('edit_sender_bank').value = withdrawal.sender_bank || '';
                document.getElementById('edit_sender_account_number').value = withdrawal.sender_account_number || '';
                document.getElementById('edit_sender_bank_address').value = withdrawal.sender_bank_address || '';
                document.getElementById('edit_reference_number').value = withdrawal.reference_number || '';
                
                // Beneficiary fields
                document.getElementById('edit_accountname').value = withdrawal.accountname || '';
                document.getElementById('edit_accountnumber').value = withdrawal.accountnumber || '';
                document.getElementById('edit_bankname').value = withdrawal.bankname || '';
                document.getElementById('edit_accounttype').value = withdrawal.Accounttype || '';
                document.getElementById('edit_swiftcode').value = withdrawal.swiftcode || '';
                document.getElementById('edit_iban').value = withdrawal.iban || '';
                document.getElementById('edit_routingnumber').value = withdrawal.routingnumber || '';
                
                // Location fields
                document.getElementById('edit_country').value = withdrawal.country || '';
                document.getElementById('edit_city').value = withdrawal.city || '';
                document.getElementById('edit_zipcode').value = withdrawal.zipcode || '';
                document.getElementById('edit_bankaddress').value = withdrawal.bankaddress || '';
                
                // Additional fields
                document.getElementById('edit_bal').value = withdrawal.bal || '';
                document.getElementById('edit_to_deduct').value = withdrawal.to_deduct || '';
                document.getElementById('edit_paydetails').value = withdrawal.paydetails || '';
                document.getElementById('edit_admin_note').value = withdrawal.admin_note || '';
                
                // Toggle fields based on type and payment mode
                toggleSenderFields();
                togglePaymentFields();
                
                // Show modal
                $('#editWithdrawalModal').modal('show');
            }
            
            function toggleSenderFields() {
                const type = document.getElementById('edit_type').value;
                const section = document.getElementById('senderFieldsSection');
                const headerText = document.getElementById('senderHeaderText');
                const nameLabel = document.getElementById('senderNameLabel');
                const bankLabel = document.getElementById('senderBankLabel');
                const accountLabel = document.getElementById('senderAccountLabel');
                const addressLabel = document.getElementById('senderAddressLabel');
                
                if (type === 'Credit') {
                    section.style.display = 'block';
                    headerText.textContent = 'Sender Information';
                    nameLabel.textContent = 'Sender Name';
                    bankLabel.textContent = 'Sender Bank';
                    accountLabel.textContent = 'Sender Account Number';
                    addressLabel.textContent = 'Sender Bank Address';
                } else {
                    // For Debits, show as Recipient
                    section.style.display = 'block';
                    headerText.textContent = 'Recipient Information';
                    nameLabel.textContent = 'Recipient Name';
                    bankLabel.textContent = 'Recipient Bank';
                    accountLabel.textContent = 'Recipient Account Number';
                    addressLabel.textContent = 'Recipient Bank Address';
                }
            }
            
            function togglePaymentFields() {
                const paymentMode = document.getElementById('edit_payment_mode').value;
                const bankFields = document.getElementById('bankTransferFields');
                const accountNumberLabel = document.getElementById('accountNumberLabel');
                
                // Show/hide bank fields based on payment mode
                if (['Wire Transfer', 'Internal Transfer', 'Domestic Transfer', 'Local transfer'].includes(paymentMode)) {
                    bankFields.style.display = 'block';
                    accountNumberLabel.innerHTML = 'Account Number <span class="text-danger">*</span>';
                } else if (['Bitcoin', 'Ethereum', 'USDT', 'Cryptocurrency'].includes(paymentMode)) {
                    bankFields.style.display = 'none';
                    accountNumberLabel.innerHTML = 'Wallet Address <span class="text-danger">*</span>';
                } else if (['PayPal', 'Skrill', 'Payoneer', 'TransferWise'].includes(paymentMode)) {
                    bankFields.style.display = 'none';
                    accountNumberLabel.innerHTML = 'Email Address <span class="text-danger">*</span>';
                } else if (paymentMode === 'Western Union') {
                    bankFields.style.display = 'none';
                    accountNumberLabel.innerHTML = 'Recipient Name <span class="text-danger">*</span>';
                } else {
                    bankFields.style.display = 'block';
                    accountNumberLabel.innerHTML = 'Account Number <span class="text-danger">*</span>';
                }
            }
        </script>
    @endsection
