<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\Form;
use App\Models\FormCategory;
use App\Models\FormFieldOrder;
use App\Models\FieldJson;
use App\Models\FormOption;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FormRelationalMigrationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that forms can be created with relational structure.
     */
    public function test_can_create_form_with_relational_structure(): void
    {
        // Create an event
        $event = Event::create([
            'name' => 'Test Event',
            'city' => 'Test City',
            'year' => 2024,
        ]);

        // Create form data
        $formData = [
            'event_id' => $event->id,
            'name' => 'Test Form',
            'description' => 'Test Description',
            'is_active' => true,
        ];

        $fieldsData = [
            [
                'key' => 'name',
                'label' => 'Name',
                'type' => 'text',
                'required' => true,
                'placeholder' => 'Enter your name',
                'validations' => ['max_elements' => 100],
            ],
            [
                'key' => 'email',
                'label' => 'Email',
                'type' => 'email',
                'required' => true,
                'placeholder' => 'Enter your email',
                'validations' => ['max_elements' => 50],
            ],
            [
                'key' => 'category',
                'label' => 'Category',
                'type' => 'select',
                'required' => true,
                'options' => [
                    ['value' => 'option1', 'label' => 'Option 1'],
                    ['value' => 'option2', 'label' => 'Option 2'],
                ],
            ],
        ];

        // Create form using the service
        $formService = app(\App\Services\FormService::class);
        $form = $formService->createFormWithRelationalData($formData, $fieldsData);

        // Assertions
        $this->assertInstanceOf(Form::class, $form);
        $this->assertEquals('Test Form', $form->name);
        $this->assertEquals($event->id, $form->event_id);

        // Check that relational data was created
        // Each field should have its own category
        $this->assertDatabaseHas('form_categories', [
            'code' => 'name',
            'name' => 'Name',
        ]);
        $this->assertDatabaseHas('form_categories', [
            'code' => 'email',
            'name' => 'Email',
        ]);
        $this->assertDatabaseHas('form_categories', [
            'code' => 'category',
            'name' => 'Category',
        ]);

        // Check field orders
        $fieldOrders = FormFieldOrder::where('form_id', $form->id)->get();
        $this->assertCount(3, $fieldOrders);

        // Check field JSON entries
        $fieldJsonEntries = FieldJson::whereIn('key', ['name', 'email', 'category'])->get();
        $this->assertCount(3, $fieldJsonEntries);

        // Check form options for select field (category field)
        $categoryFieldCategory = FormCategory::where('code', 'category')->first();
        $this->assertNotNull($categoryFieldCategory);
        $formOptions = FormOption::where('category_id', $categoryFieldCategory->id)->get();
        $this->assertCount(2, $formOptions);

        // Test getting relational fields
        $relationalFields = $form->getRelationalFields();
        $this->assertCount(3, $relationalFields);

        // Test that fields have correct structure
        $nameField = $relationalFields->firstWhere('key', 'name');
        $this->assertNotNull($nameField);
        $this->assertEquals('text', $nameField['type']);
        $this->assertTrue($nameField['required']);

        $categoryField = $relationalFields->firstWhere('key', 'category');
        $this->assertNotNull($categoryField);
        $this->assertEquals('select', $categoryField['type']);
        $this->assertCount(2, $categoryField['options']);
    }

    /**
     * Test that validation works with relational structure.
     */
    public function test_validation_works_with_relational_structure(): void
    {
        // Create an event
        $event = Event::create([
            'name' => 'Test Event',
            'city' => 'Test City',
            'year' => 2024,
        ]);

        // Create form with relational structure
        $formData = [
            'event_id' => $event->id,
            'name' => 'Test Form',
            'description' => 'Test Description',
            'is_active' => true,
        ];

        $fieldsData = [
            [
                'key' => 'name',
                'label' => 'Name',
                'type' => 'text',
                'required' => true,
                'validations' => ['max_elements' => 10],
            ],
            [
                'key' => 'email',
                'label' => 'Email',
                'type' => 'email',
                'required' => true,
            ],
        ];

        $formService = app(\App\Services\FormService::class);
        $form = $formService->createFormWithRelationalData($formData, $fieldsData);

        // Test validation rules generation
        $rules = $formService->generateValidationRulesFromRelational($form, []);
        $this->assertArrayHasKey('name', $rules);
        $this->assertArrayHasKey('email', $rules);
        $this->assertContains('required', $rules['name']);
        $this->assertContains('required', $rules['email']);
        $this->assertContains('email', $rules['email']);
    }
}
