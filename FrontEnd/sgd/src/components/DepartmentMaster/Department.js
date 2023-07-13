import React, { useState, useEffect } from 'react';
import { fetchJson } from '../../hooks/useFetchJson';
import { UpdateDepartment } from './UpdateDepartment';
import { InsertDepartment } from './InsertDepartment';
import SearchDepartment from './SearchDepartment';

export const DepartmentMaster = () => {
    //Varibles de dataes y tabla
    const [dataTable, setDataTable] = useState([]);
    const [filteredDataTable, setFilteredDataTable] = useState([]);
    
    const [currentPage, setCurrentPage] = useState(1);
    const recordsPerPage = 5;
    //variable de Modales
    const [showModalUpdate, setshowModalUpdate] = useState(false);
    const [showModalInsert, setshowModalInsert] = useState(false);
    //Varible datae selecionado
    const [selectedDataRow, setSelectedDataRow] = useState(null);


    useEffect(() => {
        fetchDataList();
    }, []);

    const fetchDataList = async () => {
        try {
            const requestData = {
                idClient: ''
            };

            const responseData = await fetchJson(
                'http://localhost:3000/app/apiDepartment/apiDepartment.php/listDepartment',
                requestData
            );
            if (Array.isArray(responseData.data) && responseData.data.length > 0) {
                setDataTable(responseData.data);
                setFilteredDataTable(responseData.data);
            } else {
                setDataTable([]);
                setFilteredDataTable([]);
            }
        } catch (error) {
            console.error(error);
            // Maneja el error de la manera que prefieras
        }
    };

    const handlePageChange = (page) => {
        setCurrentPage(page);
    };

    const indexOfLastRecord = currentPage * recordsPerPage;
    const indexOfFirstRecord = indexOfLastRecord - recordsPerPage;
    const currentRecords = filteredDataTable.slice(indexOfFirstRecord, indexOfLastRecord);

    const handleEditButtonClick = (data) => {
        
        setSelectedDataRow(data);
        setshowModalUpdate(true);
    };

    const closeModalEdit = () => {
        setshowModalUpdate(false);
        fetchDataList();
    };

    const handleInsertButtonClick = (data) => {
        setshowModalInsert(true);
    };

    const closeModalInsert = () => {
        setshowModalInsert(false);
        fetchDataList();
    };

    useEffect(() => {
        setshowModalUpdate(!!selectedDataRow);
    }, [selectedDataRow]);

    const onChangeFilter = (selectedOption) => {
        if (selectedOption !== '' ) {
          const filteredDataTable = dataTable.filter(
            (data) =>
              data.client_id === selectedOption
          );
          setFilteredDataTable(filteredDataTable);
        } else {
          setFilteredDataTable(dataTable);
        }
        setCurrentPage(1);
      };

    return (

        <div>
            <section className="content-header">
                <div className="container-fluid">
                    <div className="row mb-2">
                        <div className="col-sm-6">
                            <h1>Administración de departamentos</h1>
                        </div>
                    </div>
                </div>
            </section>
            <SearchDepartment dataTable={dataTable} onChangeFilter={onChangeFilter} />
            <section className="content">
                <div className="card card-solid">
                    <div className="card card-success">
                        <div className="card-header">
                            <h3 className="card-title">Lista de departamentos</h3>
                        </div>
                        <div className="card-body">
                            <div
                                id="example1_wrapper"
                                className="dataTables_wrapper dt-bootstrap4"
                            >
                                <div className="row">
                                    <div className="col-sm-12 col-md-6 ">
                                        <div className="dt-buttons btn-group flex-wrap">
                                            <button className="btn btn-secondary"
                                                tabIndex={0}
                                                aria-controls="tbActivedataTable"
                                                type="button"
                                                onClick={handleInsertButtonClick}>
                                                <span><i className="nav-icon fas fa-plus"></i></span>
                                            </button>
                                        </div>
                                    </div>
                                    <div className="col-sm-12 col-md-6 d-flex justify-content-end">
                                        <div
                                            id="example1_filter"
                                            className="dataTables_filter"
                                        >
                                        </div>
                                    </div>
                                </div>
                                <div className="row">
                                    <div className="col-sm-12">
                                        <table
                                            id="example1"
                                            className="table table-bordered table-striped dataTable dtr-inline"
                                            role="grid"
                                            aria-describedby="example1_info"
                                        >
                                            <thead>
                                                <tr role="row">
                                                    <th
                                                        className="sorting_asc"
                                                        tabIndex="0"
                                                        aria-controls="example1"
                                                        rowSpan="1"
                                                        colSpan="1"
                                                        aria-sort="ascending"
                                                        aria-label="Rendering engine: activate to sort column descending"
                                                    >
                                                        ID
                                                    </th>
                                                    <th
                                                        className="sorting_asc"
                                                        tabIndex="0"
                                                        aria-controls="example1"
                                                        rowSpan="1"
                                                        colSpan="1"
                                                        aria-sort="ascending"
                                                        aria-label="Rendering engine: activate to sort column descending"
                                                    >
                                                        NOMBRE
                                                    </th>
                                                    <th
                                                        className="sorting"
                                                        tabIndex="0"
                                                        aria-controls="example1"
                                                        rowSpan="1"
                                                        colSpan="1"
                                                        aria-label="Browser: activate to sort column ascending"
                                                    >
                                                        NOMBRE
                                                    </th>
                                                    <th
                                                        className="sorting"
                                                        tabIndex="0"
                                                        aria-controls="example1"
                                                        rowSpan="1"
                                                        colSpan="1"
                                                        aria-label="Browser: activate to sort column ascending"
                                                    >
                                                        PADRE
                                                    </th>
                                                    <th
                                                        className="sorting"
                                                        tabIndex="0"
                                                        aria-controls="example1"
                                                        rowSpan="1"
                                                        colSpan="1"
                                                        aria-label="Browser: activate to sort column ascending"
                                                    >
                                                        ESTADO
                                                    </th>
                                                    <th
                                                        className="sorting"
                                                        tabIndex="0"
                                                        aria-controls="example1"
                                                        rowSpan="1"
                                                        colSpan="1"
                                                        aria-label="Browser: activate to sort column ascending"
                                                    >
                                                        ACCIONES
                                                    </th>
                                                    <th
                                                        className="sorting"
                                                        tabIndex="0"
                                                        aria-controls="example1"
                                                        rowSpan="1"
                                                        colSpan="1"
                                                        aria-label="Platform(s): activate to sort column ascending"
                                                        style={{ display: 'none' }}
                                                    >
                                                        STATUS_ID
                                                    </th>
                                                    <th
                                                        className="sorting"
                                                        tabIndex="0"
                                                        aria-controls="example1"
                                                        rowSpan="1"
                                                        colSpan="1"
                                                        aria-label="Platform(s): activate to sort column ascending"
                                                        style={{ display: 'none' }}
                                                    >
                                                        ID_CLIENT
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                {currentRecords.map((data) => (
                                                    <tr key={data.department_id}>
                                                        <td>{data.department_id}</td>
                                                        <td>{data.client_name}</td>
                                                        <td>{data.department_name}</td>
                                                        <td>{data.father_id}</td>
                                                        <td>{data.reference_name}</td>
                                                        <td className="text-center">
                                                            <button
                                                                type="button"
                                                                className="btnEdit btn btn-sm btn-outline-primary mr-1"
                                                                title="Editar"
                                                                onClick={() => handleEditButtonClick(data)}
                                                            >
                                                                <i
                                                                    className="nav-icon far fa-edit"
                                                                    aria-hidden="true"
                                                                ></i>
                                                            </button>
                                                        </td>
                                                        <td style={{ display: 'none' }}>
                                                            {data.department_status_id}
                                                        </td>
                                                        <td style={{ display: 'none' }}>
                                                            {data.client_id}
                                                        </td>
                                                    </tr>
                                                ))}
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div className="row">
                                    <div className="col-sm-12 col-md-5">
                                        <div
                                            className="dataTables_info"
                                            id="example1_info"
                                            role="status"
                                            aria-live="polite"
                                        >
                                            Mostrando {indexOfFirstRecord + 1} a{' '}
                                            {Math.min(indexOfLastRecord, filteredDataTable.length)} de{' '}
                                            {filteredDataTable.length} registros
                                        </div>
                                    </div>
                                    <div className="col-sm-12 col-md-7">
                                        <div
                                            className="dataTables_paginate paging_simple_numbers"
                                            id="example1_paginate"
                                        >
                                            <ul className="pagination">
                                                <li
                                                    className={`paginate_button page-item ${currentPage === 1 ? 'disabled' : ''
                                                        }`}
                                                >
                                                    <button
                                                        className="page-link"
                                                        onClick={() => handlePageChange(currentPage - 1)}
                                                    >
                                                        Anterior
                                                    </button>
                                                </li>
                                                {Array.from(
                                                    { length: Math.ceil(filteredDataTable.length / recordsPerPage) },
                                                    (_, i) => (
                                                        <li
                                                            className={`paginate_button page-item ${currentPage === i + 1 ? 'active' : ''
                                                                }`}
                                                            key={i}
                                                        >
                                                            <button
                                                                className="page-link"
                                                                onClick={() => handlePageChange(i + 1)}
                                                            >
                                                                {i + 1}
                                                            </button>
                                                        </li>
                                                    )
                                                )}
                                                <li
                                                    className={`paginate_button page-item ${currentPage ===
                                                        Math.ceil(filteredDataTable.length / recordsPerPage)
                                                        ? 'disabled'
                                                        : ''
                                                        }`}
                                                >
                                                    <button
                                                        className="page-link"
                                                        onClick={() => handlePageChange(currentPage + 1)}
                                                    >
                                                        Siguiente
                                                    </button>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            {showModalUpdate && (
                <UpdateDepartment selectedDataRow={selectedDataRow} closeModalEdit={closeModalEdit} />
            )}
            {showModalInsert && (
                <InsertDepartment closeModalInsert={closeModalInsert} />
            )}
        </div >
    );
};
