@extends('layouts.base')

@section('content')
<div class="row">

    <div class="col-12 text-center mb-5">
        <h1>Lista de Empleados</h1>
    </div>

    <div class="col-12">
        <table class="table table-striped table-hover table-bordered table-dark" id="employees-table" 
        style="box-shadow: 0px 0px 10px rgba(255, 255, 255, 0.2)">
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
    </div>

    <div class="row">
        <div class="col-6">
            <!-- Mostrar cantidad total de registros -->
            <div id="total-records" class="text-start mb-3"></div>
        </div>

        <div class="col-6">
            <!-- Paginador -->
            <nav aria-label="Page navigation example">
                <ul class="pagination justify-content-end pagination-dark" id="pagination">
                    <!-- Los elementos de paginación se insertarán aquí dinámicamente -->
                </ul>
            </nav>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', async () => {
            const loadEmployees = async (url) => {
                try {
                    const response = await axios.get(url);
                    const employees = response.data.employees.data;
                    const pagination = response.data.employees.links;
                    const totalRecords = response.data.employees.total;
                    const currentPage = response.data.employees.current_page;
                    const perPage = response.data.employees.per_page;

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

                    // Mostrar cantidad total de registros
                    const firstItem = (currentPage - 1) * perPage + 1;
                    const lastItem = firstItem + employees.length - 1;
                    document.querySelector('#total-records').innerText = `Mostrando ${firstItem} a ${lastItem} de un total de ${totalRecords} registros`;

                    // Generar paginación
                    pagination.forEach(link => {
                        const pageItem = document.createElement('li');
                        pageItem.classList.add('page-item');
                        if (link.active) {
                            pageItem.classList.add('active');
                        }

                        // Reemplazar texto "Previous" y "Next" por iconos
                        let label = link.label;
                        if (label.includes('Previous')) {
                            label = '<i class="bi bi-chevron-left"></i>';
                        } else if (label.includes('Next')) {
                            label = '<i class="bi bi-chevron-right"></i>';
                        }

                        pageItem.innerHTML = `
                            <a class="page-link bg-dark text-white" href="#" data-url="${link.url}">${label}</a>
                        `;
                        paginationElement.appendChild(pageItem);
                    });

                    // Agregar evento de clic a los enlaces de paginación
                    document.querySelectorAll('.page-link').forEach(link => {
                        link.addEventListener('click', (e) => {
                            e.preventDefault();
                            const url = e.target.closest('a').getAttribute('data-url');
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
