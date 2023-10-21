<?php

namespace App\Tests\Service;

use App\Dto\Address as AddressDto;
use App\Dto\Dimensions as DimensionsDto;
use App\Dto\FullName as FullNameDto;
use App\Dto\Recipient as RecipientDto;
use App\Dto\Sender as SenderDto;
use App\Dto\ParcelDto;
use App\Entity\Dimensions;
use App\Entity\Parcel;
use App\Entity\Recipient;
use App\Entity\Sender;
use App\Repository\ParcelRepository;
use App\Service\ParcelService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class ParcelServiceTest extends TestCase
{
    private ParcelRepository $parcelRepository;
    private ParcelService $parcelService;
    private EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->parcelRepository = $this->createMock(ParcelRepository::class);
        $this->parcelService = new ParcelService($this->parcelRepository, $this->entityManager);
    }

    public function generateParcels(): array
    {
        return [
            new ParcelDto(
                new SenderDto(
                    new FullNameDto('Bob', 'Billy', 'Radison'),
                    '+39998881111',
                    new AddressDto('USA', 'New-York', 'St. 12th', '312', '3')
                ),
                new RecipientDto(
                    new FullNameDto('Albert', 'Hill', 'Harrison'),
                    '+12125551234',
                    new AddressDto('USA', 'Los-Angeles', 'St. Beach', '666', '321')
                ),
                new DimensionsDto(10, 2, 3, 1),
                31231
            ),
            new ParcelDto(
                new SenderDto(
                    new FullNameDto('Jack', 'Thompson', 'McLaughlin'),
                    '+3224056688',
                    new AddressDto('Belgium', 'Brussels', 'Rue Masui', '182', '9')
                ),
                new RecipientDto(
                    new FullNameDto('Nicholas', 'Fitzgerald', 'Albert'),
                    '+42042021234567',
                    new AddressDto('Czech Republic', 'Prague', 'Navratilova', '123', '321')
                ),
                new DimensionsDto(3, 1, 1, 6),
                10000
            ),
        ];
    }

    public function testAddParcel()
    {
        $dto = $this->generateParcels();
        $parcel = array_shift($dto);

        $parcelAdded = $this->parcelService->add(array_shift($dto));

        $this->assertInstanceOf(Parcel::class, $parcelAdded);
        $this->assertInstanceOf(Sender::class, $parcelAdded->getSender());
        $this->assertInstanceOf(Recipient::class, $parcelAdded->getRecipient());
        $this->assertInstanceOf(Dimensions::class, $parcelAdded->getDimensions());
        $this->assertEquals($parcel['valuation'], $parcelAdded->getValuation());
    }

    public function testSearchBySenderPhone()
    {
        $searchType = 'sender_phone';
        $query = '+3224056688';

        $data = $this->generateParcels();

        $this->parcelRepository->expects($this
            ->once())
            ->method('findBy')
            ->with($query)
            ->willReturn($data);

        $parcels = $this->parcelService->search($searchType, $query);

        $this->assertCount(2, $parcels);
    }

    public function testSearchByRecipientName()
    {
        $searchType = 'recipient_name';
        $query = 'Nicholas Fitzgerald Albert';

        $array = $this->generateParcels();
        $dto = array_shift($array);

        $this->parcelRepository->expects($this
            ->once())
            ->method('findBy')
            ->with($query)
            ->willReturn($dto);
        $parcels = $this->parcelService->search($searchType, $query);

        $this->assertCount(1, $parcels);
    }

    public function testDeleteParcelNotFound()
    {
        $id = '666';

        $this->parcelRepository->expects($this
            ->once())
            ->method('findOneBy')
            ->with(['id' => $id])
            ->willReturn(null);

        $result = $this->parcelService->delete($id);

        $this->assertEquals('No parcel found', $result);
    }

    public function testDeleteParcelInvalidId()
    {
        $id = null;
        $dto = $this->generateParcels();

        $this->parcelRepository->expects($this
            ->once())
            ->method('findOneBy')
            ->with(['id' => $id])
            ->willReturn(null);

        $result = $this->parcelService->delete($id);

        $this->assertEquals('Invalid parcel ID provided', $result);
    }

    public function testDeleteParcel()
    {
        $id = '1';
        $dto = $this->generateParcels();
        $parcel = array_shift($dto);

        $this->parcelRepository->expects($this
            ->once())
            ->method('findOneBy')
            ->with(['id' => $id])
            ->willReturn($parcel);

        $this->entityManager->expects($this->once())->method('remove')->with($parcel);
        $this->entityManager->expects($this->once())->method('flush');

        $result = $this->parcelService->delete($id);

        $this->assertTrue($result);
    }
}
