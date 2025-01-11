@extends('layouts.base')

@section('content')
<div class="row">
    <h1>Lista de Empleados</h1>

    <table class="table table-striped table-hover table-bordered table-dark" id="employees-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>RUT</th>
                <th>Nombre</th>
                <th>Apellido</th>
            </tr>
        </thead>
        <tbody>
            <!-- Los datos se insertarán aquí dinámicamente -->
        </tbody>
    </table>

    <!-- Paginador -->
    <nav aria-label="Page navigation example">
        <ul class="pagination justify-content-center pagination-dark" id="pagination">
            <!-- Los elementos de paginación se insertarán aquí dinámicamente -->
        </ul>
    </nav>

    <script>
        document.addEventListener('DOMContentLoaded', async () => {
            const loadEmployees = async (url) => {
                try {
                    const response = await axios.get(url);
                    const employees = response.data.employees.data;
                    const pagination = response.data.employees.links;

                    // Limpiar la tabla y el paginador
                    const tableBody = document.querySelector('#employees-table tbody');
                    tableBody.innerHTML = '';
                    const paginationElement = document.querySelector('#pagination');
                    paginationElement.innerHTML = '';

                    // Insertar los datos en la tabla
                    employees.forEach(employee => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${employee.id}</td>
                            <td>${employee.rut}</td>
                            <td>${employee.first_name}</td>
                            <td>${employee.last_name}</td>
                        `;
                        tableBody.appendChild(row);
                    });

                    // Generar paginación
                    pagination.forEach(link => {
                        const pageItem = document.createElement('li');
                        pageItem.classList.add('page-item');
                        if (link.active) {
                            pageItem.classList.add('active');
                        }
                        pageItem.innerHTML = `
                            <a class="page-link bg-dark text-white" href="#" data-url="${link.url}">${link.label}</a>
                        `;
                        paginationElement.appendChild(pageItem);
                    });

                    // Agregar evento de clic a los enlaces de paginación
                    document.querySelectorAll('.page-link').forEach(link => {
                        link.addEventListener('click', (e) => {
                            e.preventDefault();
                            const url = e.target.getAttribute('data-url');
                            if (url) {
                                loadEmployees(url);
                            }
                        });
                    });
                } catch (error) {
                    console.error('Error al consumir la API:', error);
                }
            };

            // Cargar la primera página de empleados
            loadEmployees('http://localhost:8000/api/employee');
        });
    </script>
</div>
@endsection
