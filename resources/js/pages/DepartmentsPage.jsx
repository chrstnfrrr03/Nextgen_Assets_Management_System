import React from 'react';
import CRUDPage from '../components/CRUDPage';

export default function DepartmentsPage() {
    const fields = [
        { name: 'name', label: 'Department Name', required: true },
        {
            name: 'description',
            label: 'Description',
            type: 'textarea',
            rows: 3,
            required: false,
            fullWidth: true,
        },
    ];

    return (
        <CRUDPage
            title="Departments"
            endpoint="departments"
            fields={fields}
            searchPlaceholder="Search departments..."
            createLabel="Add Department"
            csvConfig={{
                filename: 'departments.csv',
                headers: ['Department Name', 'Description'],
                mapRow: (item) => ({
                    'Department Name': item.name || '',
                    Description: item.description || '',
                }),
            }}
        />
    );
}