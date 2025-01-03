import {message} from 'antd'
import {useCallback, useMemo} from 'react'
import {useLazyCreateSubject, useLazyDeleteSubject, useLazyUpdateSubject} from 'shared/libs/query'
import {EditableListWidget} from 'widgets/editableListWidget/EditableListWidget'
import {EditableItem} from 'widgets/editableListWidget/EditableRow'
import {Preloader} from '../preloader/Preloader'
import {useInitializeSubjects} from './libs/useInitializeSubjects'

const SubjectsList = () => {
	const [createSubject] = useLazyCreateSubject()
	const [updateSubject] = useLazyUpdateSubject()
	const [deleteSubject] = useLazyDeleteSubject()
	const {loading, subjects, refetch} = useInitializeSubjects()

	const handleResponse = useCallback(async (promise: Promise<any>, successMessage: string) => {
		const data = await promise
		if (data.isError) {
			message.error('Что-то пошло не так. Попробуйте ещё раз.')
			return false
		}
		message.success(successMessage)
		refetch()
		return true
	}, [refetch])

	const handleSave = async (newName: string, id?: string) => {
		if (id) {
			await handleResponse(updateSubject({subjectId: id, name: newName}), 'Предмет успешно обновлён.')
		}
		else {
			await handleResponse(createSubject({name: newName}), 'Предмет успешно добавлен.')
		}
	}

	const handleDelete = async (id: string) => {
		await handleResponse(deleteSubject({subjectId: id}), 'Предмет успешно удалён.')
	}

	const items = useMemo(
		(): EditableItem[] =>
			subjects.map(subject => ({id: subject.subjectId, name: subject.name})),
		[subjects],
	)

	if (loading) {
		return <Preloader/>
	}

	return (
		<div>
			<EditableListWidget
				title="Предметы"
				data={items}
				onSave={handleSave}
				onDelete={handleDelete}
			/>
		</div>
	)
}

export {
	SubjectsList,
}
