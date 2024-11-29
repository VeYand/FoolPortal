import {Modal, Form, Input, Select, Button} from 'antd'
import {useEffect, useMemo, useState} from 'react'
import {GroupData, SubjectData, TeacherSubjectData, USER_ROLE, UserData} from 'shared/types'

const {Option} = Select

type UserFormModalProps = {
	user?: UserData,
	onClose: () => void,
	onSave: (user: UserData, assignedSubjects: TeacherSubjectData[], groupIds: string[]) => void,
	groups: GroupData[],
	subjects: SubjectData[],
	teacherSubjects: TeacherSubjectData[],
}

const UserFormModal = ({
	user,
	onClose,
	onSave,
	groups = [],
	subjects = [],
	teacherSubjects = [],
}: UserFormModalProps) => {
	const [form] = Form.useForm()
	const [isTeacher, setIsTeacher] = useState(user?.role === USER_ROLE.TEACHER)
	const [isStudent, setIsStudent] = useState(user?.role === USER_ROLE.STUDENT)
	const [selectedGroupIds, setSelectedGroupIds] = useState<string[]>(user?.groupIds ?? [])

	const assignedSubjectIds = useMemo(
		() => teacherSubjects.filter(ts => ts.teacherId === user?.userId).map(ts => ts.subjectId),
		[teacherSubjects, user],
	)

	const handleRoleChange = (role: USER_ROLE) => {
		setIsTeacher(role === USER_ROLE.TEACHER)
		setIsStudent(role === USER_ROLE.STUDENT)
		if (role !== USER_ROLE.TEACHER) {
			form.setFieldsValue({subjects: []})
		}
		if (role !== USER_ROLE.STUDENT) {
			form.setFieldsValue({groups: []})
		}
	}

	useEffect(() => {
		if (user) {
			form.setFieldsValue({
				...user,
				subjects: assignedSubjectIds,
			})
		}
	}, [user, assignedSubjectIds, form])

	const handleSubmit = () => {
		form.validateFields().then(values => {
			const updatedUser = {...user, ...values}
			const updatedTeacherSubjects
				= values.role === USER_ROLE.TEACHER
					? values.subjects?.map((subjectId: string) => ({
						teacherSubjectId:
						teacherSubjects.find(
							ts => ts.subjectId === subjectId && ts.teacherId === user?.userId,
						)?.teacherSubjectId || `ts-${subjectId}-${user?.userId}`,
						teacherId: user?.userId || 'new-teacher-id',
						subjectId,
					})) || []
					: []
			const updatedGroupIds = values.groups || []
			onSave(updatedUser, updatedTeacherSubjects, updatedGroupIds)
			onClose()
		})
	}

	return (
		<Modal
			title={user ? 'Редактировать пользователя' : 'Создать пользователя'}
			visible
			onCancel={onClose}
			footer={null}
		>
			<Form
				form={form}
				initialValues={user}
				onValuesChange={changedValues => {
					if (changedValues.role) {
						handleRoleChange(changedValues.role)
					}
					if (changedValues.groupIds) {
						setSelectedGroupIds(changedValues.groupIds)
					}
				}}
			>
				<Form.Item
					name="firstName"
					label="Имя"
					rules={[{required: true, message: 'Пожалуйста, введите имя!'}]}
				>
					<Input />
				</Form.Item>
				<Form.Item
					name="lastName"
					label="Фамилия"
					rules={[{required: true, message: 'Пожалуйста, введите фамилию!'}]}
				>
					<Input />
				</Form.Item>
				<Form.Item
					name="role"
					label="Роль"
					rules={[{required: true, message: 'Пожалуйста, выберите роль!'}]}
				>
					<Select>
						<Option value="STUDENT">{'Студент'}</Option>
						<Option value="TEACHER">{'Преподаватель'}</Option>
						<Option value="ADMIN">{'Администратор'}</Option>
					</Select>
				</Form.Item>
				<Form.Item
					name="email"
					label="Email"
					rules={[
						{type: 'email', required: true, message: 'Пожалуйста, введите корректный email!'},
					]}
				>
					<Input />
				</Form.Item>
				{isTeacher && (
					<Form.Item
						name="subjects"
						label="Предметы"
						rules={[{required: true, message: 'Выберите хотя бы один предмет!'}]}
					>
						<Select mode="multiple" placeholder="Выберите предметы">
							{subjects.map(subject => (
								<Option key={subject.subjectId} value={subject.subjectId}>
									{subject.name}
								</Option>
							))}
						</Select>
					</Form.Item>
				)}
				{isStudent && (
					<Form.Item name="groupIds" label="Группы">
						<Select mode="multiple" placeholder="Выберите группы">
							{groups.map(group => (
								<Option key={group.groupId} value={group.groupId} disabled={selectedGroupIds.includes(group.groupId)}>
									{group.name}
								</Option>
							))}
						</Select>
					</Form.Item>
				)}
				<Button type="primary" onClick={handleSubmit} style={{marginTop: 16}}>
					{'Сохранить'}
				</Button>
			</Form>
		</Modal>
	)
}

export {
	UserFormModal,
}
