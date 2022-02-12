<?php

namespace Signalfire\Shopengine\Http\Controllers\API;

use Illuminate\Http\Request;

use Signalfire\Shopengine\Http\Resources\AddressResource;
use Signalfire\Shopengine\Interfaces\AddressRepositoryInterface;
use Signalfire\Shopengine\Http\Requests\StoreAddressRequest;
use Signalfire\Shopengine\Http\Requests\UpdateAddressRequest;

use Signalfire\Shopengine\Models\Address;

class AddressController extends Controller
{
    private AddressRepositoryInterface $addressRepository;

    public function __construct(AddressRepositoryInterface $addressRepository)
    {
        $this->addressRepository = $addressRepository;
    }

    /**
     * Gets all addresses for user.
     *
     * @return string JSON
     */
    public function index(Request $request)
    {
        $addresses = $this->addressRepository->getAddresses($request->user());

        return (AddressResource::collection($addresses))
            ->response()
            ->setStatusCode(200);
    }

    /**
     * Gets address.
     *
     * @param Address $address
     *
     * @return string JSON
     */
    public function show(Address $address)
    {
        return (new AddressResource($address))
            ->response()
            ->setStatusCode(200);
    }

    /**
     * Creates a new address.
     *
     * @param StoreAddressRequest $request
     * 
     * @return string JSON
     */
    public function store(StoreAddressRequest $request)
    {
        $address = $this->addressRepository->createAddress(
            $request->user(), $request->validated()
        );

        return (new AddressResource($address))
            ->response()
            ->setStatusCode(201);

    }

    /**
     * Updates existing address.
     *
     * @param UpdateAddressRequest $request
     * 
     * @param Address $address
     *
     * @return string JSON
     */
    public function update(UpdateAddressRequest $request, Address $address)
    {
        $address = $this->addressRepository->updateAddress($address, $request->validated());

        return (new AddressResource($address))
            ->response()
            ->setStatusCode(204);
    }    
}