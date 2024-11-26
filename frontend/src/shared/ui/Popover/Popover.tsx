import {Popover as AntdPopover, Button, Typography} from 'antd'
import {ReactNode} from 'react'

type PopoverItem = {
	onClick: () => void,
	label: string,
}

type PopoverProps = {
	items: PopoverItem[],
	children: ReactNode,
}

const Popover = ({items, children}: PopoverProps) => (
	<AntdPopover
		content={
			<div>
				{items.map((item, index) => (
					<Button
						key={index}
						type="link"
						onClick={item.onClick}
						style={{display: 'block', textAlign: 'left', padding: 0, width: '100%'}}
					>
						<Typography.Text>{item.label}</Typography.Text>
					</Button>
				))}
			</div>
		}
		trigger="hover"
		placement="bottomRight"
	>
		{children}
	</AntdPopover>
)

export {
	Popover,
	type PopoverItem,
}
