import React, { useState, useEffect, useContext } from 'react';
import { fetchJson } from '../../../hooks/useFetchJson';
import { UpdateAssignAction } from './UpdateAssignAction';
import { InsertAssignAction } from './InsertAssignAction';
import { DeleteAssignAction } from './DeleteAssignAction';
import { AuthContext } from '../../Context/AuthContext';


export const AssignAction = ({ selectedDataRow, closeTabsAssign, handleReload }) => {
    const { loginData } = useContext(AuthContext);
    //Varibles de dataes y tabla
    const [dataTable, setDataTable] = useState([]);
    const [filteredDataTable, setFilteredDataTable] = useState([]);

    const [currentPage, setCurrentPage] = useState(1);
    const recordsPerPage = 5;
    //variable de Modales
    const [showModalUpdate, setshowModalUpdate] = useState(false);
    const [showModalInsert, setshowModalInsert] = useState(false);
    const [showModalDelete, setshowModalDelete] = useState(false);

    const [selectedDataRow2, setSelectedDataRow2] = useState(null);
    //Varible datae selecionado
    // const [selectedDataRow, setSelectedDataRow] = useState(null);


    useEffect(() => {
        fetchDataList();
       
    }, []);

    const fetchDataList = async () => {
        try {
            const requestData = {
                idClient: loginData.clientId,
                idProcess: selectedDataRow.process_id
            };

            const responseData = await fetchJson(
                'http://localhost:3000/app/apiActionProcess/apiActionProcess.php/listActionProcess',
                requestData
            );

            if (Array.isArray(responseData.data) && responseData.data.length > 0) {
                setDataTable(responseData.data);
                setFilteredDataTable(responseData.data);
                handleReload();
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
        setSelectedDataRow2(data);
        setshowModalUpdate(true);
    };

    const handleDeleteButtonClick = (data) => {
        setSelectedDataRow2(data);
        setshowModalDelete(true);
    };

    const closeModalEdit = () => {
        setshowModalUpdate(false);
        fetchDataList();
    };

    const closeModalInsert = () => {
        setshowModalInsert(false);
        fetchDataList();
    };

    const closeModalDelete = () => {
        setshowModalDelete(false);
        fetchDataList();
    };

    const handleInsertButtonClick = (data) => {
        setshowModalInsert(true);
    };

    return (

        <div>
            <div className="row">
                <div className="col-12">
                    <div className="card card-success">
                        <div className="card-header">
                            <h3 className="card-title">Asignar acción a proceso: <strong>{selectedDataRow.process_name}</strong></h3>
                            <div className="card-tools">
                                <button type="button"
                                    className="btn btn-tool"
                                    data-card-widget="collapse"
                                    title="Minimizar"
                                >
                                    <i className="fas fa-minus"></i>
                                </button>
                                <button type="button"
                                    className="btn btn-tool"
                                    data-card-widget="remove"
                                    title="Cerrar"
                                    onClick={closeTabsAssign}
                                >
                                    <i className="fas fa-times"></i>
                                </button>
                            </div>
                        </div>

                        <div className="card-body" style={{ display: 'block' }}>

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
                                                        ACCIÓN
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
                                                        PROCESS_ID
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
                                                        ACTION_ID
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                {currentRecords.map((data) => (
                                                    <tr key={`${data.action_id}-${data.process_id}`}>
                                                        <td>{data.action_name}</td>
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
                                                            <button
                                                            type="button"
                                                            className="btnDelete btn btn-sm btn-outline-danger mr-1"
                                                            title="Eliminar"
                                                            onClick={() => handleDeleteButtonClick(data)}
                                                        >
                                                            <i
                                                                className="nav-icon fas fa-trash"
                                                                aria-hidden="true"
                                                            ></i>
                                                        </button>
                                                        </td>
                                                       
                                                        <td style={{ display: 'none' }}>
                                                            {data.process_id}
                                                        </td>
                                                        <td style={{ display: 'none' }}>
                                                            {data.action_id}
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

                        {showModalUpdate && (
                            <UpdateAssignAction selectedDataRow={selectedDataRow2} closeModalEdit={closeModalEdit} />
                        )}
                        {showModalInsert && (
                            <InsertAssignAction selectedDataRow={selectedDataRow} closeModalInsert={closeModalInsert} />
                        )}
                         {showModalDelete && (
                            <DeleteAssignAction selectedDataRow={selectedDataRow2} closeModalDelete={closeModalDelete} />
                        )}

                    </div>
                </div>
            </div>
        </div >
    );
};
